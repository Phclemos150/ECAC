<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

// ══════════════════════════════════════════════════════════════════
// EXCEÇÕES CUSTOMIZADAS
// ══════════════════════════════════════════════════════════════════

class ErroLoginException extends RuntimeException
{
    public function __construct(
        private string $titulo,
        string $mensagem
    ) {
        parent::__construct($mensagem);
    }
    public function getTitulo(): string
    {
        return $this->titulo;
    }
}

class ErroCadastroException extends RuntimeException
{
    public function __construct(
        private string $titulo,
        string $mensagem,
        private bool $redirecionarLogin = false
    ) {
        parent::__construct($mensagem);
    }
    public function getTitulo(): string
    {
        return $this->titulo;
    }
    public function deveRedirecionarLogin(): bool
    {
        return $this->redirecionarLogin;
    }
}


// ══════════════════════════════════════════════════════════════════
// CONTROLLER DE AUTENTICAÇÃO
// ══════════════════════════════════════════════════════════════════

class AutentController
{
    private UsuarioModel $usuarioModel;

    public function __construct($con)
    {
        $this->usuarioModel = new UsuarioModel($con);
    }


    // ──────────────────────────────────────────────────────────────
    // LOGIN
    // ──────────────────────────────────────────────────────────────
    public function login(): void
    {
        $this->iniciarSessao();

        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        try {
            if (empty($email) || empty($senha)) {
                $this->usuarioModel->registrarLogSistema(null, null, "FALHA LOGIN (Campos vazios)");
                throw new ErroLoginException("Erro de Validação", "Todos os campos devem ser preenchidos!");
            }

            if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/', $email)) {
                $this->usuarioModel->registrarLogSistema(null, null, "FALHA LOGIN (E-mail com formato inválido): $email");
                throw new ErroLoginException("Erro de Login", "Email ou senha inválidos!");
            }

            $usuario = $this->usuarioModel->buscarUsuarioPorEmail($email);

            if (!$usuario) {
                $_SESSION['redirecionar_cadastro'] = true;
                $this->usuarioModel->registrarLogSistema(null, null, "FALHA LOGIN (Usuário não cadastrado): $email");
                throw new ErroLoginException("Erro de Autenticação", "Usuário não cadastrado!");
            }

            if ($usuario['status_conta'] !== 'ativo') {
                $this->usuarioModel->registrarLogSistema($usuario['id_usuario'], $usuario['id_funcao'], "FALHA LOGIN (Conta Inativa): $email");
                throw new ErroLoginException("Conta Inativa", "Entre em contato com o suporte.");
            }

            if (!password_verify($senha, $usuario['senha_hash'])) {
                $this->usuarioModel->registrarLogSistema($usuario['id_usuario'], $usuario['id_funcao'], "FALHA LOGIN (Senha Incorreta): $email");
                throw new ErroLoginException("Erro de Login", "Email ou senha incorretos!");
            }

            // Previne Session Hijacking
            session_regenerate_id(true);

            // Busca permissões RBAC
            $permissoes = $this->buscarPermissoes((int) $usuario['id_usuario']);

            // Salva dados e permissões na sessão
            $_SESSION['user_logado'] = [
                'id' => $usuario['id_usuario'],
                'nome' => $usuario['nome_usuario'],
                'email' => $usuario['email'],
                'foto' => $usuario['foto_perfil'] ?? null,
                'id_funcao' => $usuario['id_funcao'],
                'nome_funcao' => $usuario['nome_funcao'],
                'permissoes' => $permissoes, // Armazena nomes (strings) para legibilidade
            ];

            // LOG DE SUCESSO
            $this->usuarioModel->registrarLogSistema($usuario['id_usuario'], $usuario['id_funcao'], "LOGIN BEM SUCEDIDO");

            // Redireciona para o painel principal
            header('Location: ../views/painel.php');
            exit;

        } catch (ErroLoginException $e) {
            $_SESSION['modal_erro_titulo'] = $e->getTitulo();
            $_SESSION['modal_erro_mensagem'] = $e->getMessage();
            header('Location: ../views/login.php');
            exit;
        }
    }


    // ──────────────────────────────────────────────────────────────
    // CADASTRO
    // ──────────────────────────────────────────────────────────────
    public function cadastro(): void
    {
        $this->iniciarSessao();

        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');
        $doc = trim($_POST['documento'] ?? '');

        try {
            $dados = [
                'nome' => trim($_POST['nome_usuario'] ?? ''),
                'email' => $email,
                'senha_hash' => !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : '',
                'documento' => $doc,
                'data_nascimento' => implode('-', array_reverse(explode('/', $_POST['data_nascimento'] ?? ''))),
                'telefone' => trim($_POST['telefone'] ?? ''),
                'instagram' => trim($_POST['instagram'] ?? ''),
                'grau_academico' => trim($_POST['grau_academico'] ?? ''),
                'nome_curso' => trim($_POST['nome_curso'] ?? ''),
                'cidade' => trim($_POST['cidade'] ?? ''),
                'estado' => trim($_POST['estado'] ?? ''),
                'pais' => trim($_POST['pais'] ?? ''),
                'foto_perfil' => null,
            ];

            $camposObrigatorios = [
                'nome', 'email', 'senha_hash', 'documento', 'data_nascimento',
                'grau_academico', 'nome_curso', 'cidade', 'estado', 'pais',
            ];

            foreach ($camposObrigatorios as $campo) {
                if (empty(trim((string) $dados[$campo]))) {
                    $this->usuarioModel->registrarLogSistema(null, null, "FALHA CADASTRO (Campos vazios): $email");
                    throw new ErroCadastroException("Erro de Validação", "Todos os campos devem ser preenchidos!");
                }
            }

            if ($this->usuarioModel->verificarDados('email', $email)) {
                $this->usuarioModel->registrarLogSistema(null, null, "FALHA CADASTRO (E-mail duplicado): $email");
                throw new ErroCadastroException(
                    "Erro de Cadastro",
                    "Os dados informados já possuem uma conta vinculada. Verifique suas informações!",
                    true
                );
            }

            if ($this->usuarioModel->verificarDados('documento', $doc)) {
                $this->usuarioModel->registrarLogSistema(null, null, "FALHA CADASTRO (Documento duplicado): $doc");
                throw new ErroCadastroException(
                    "Erro de Cadastro",
                    "Os dados informados já possuem uma conta vinculada. Verifique suas informações!",
                    true
                );
            }

            $dados['foto_perfil'] = $this->processarFotoPerfil();

            if (!$this->usuarioModel->cadastrarUsuario($dados)) {
                $this->usuarioModel->registrarLogSistema(null, null, "FALHA CADASTRO (Erro de Banco de Dados): $email");
                throw new ErroCadastroException("Erro de Cadastro", "Não foi possível concluir o cadastro. Tente novamente!");
            }

            // LOG DE SUCESSO (Buscamos o usuário gerado para pegar o ID real dele)
            $novoUser = $this->usuarioModel->buscarUsuarioPorEmail($email);
            if($novoUser) {
                $this->usuarioModel->registrarLogSistema($novoUser['id_usuario'], $novoUser['id_funcao'], "CADASTRO BEM SUCEDIDO");
            }

            $_SESSION['modal_sucesso_titulo'] = "Cadastro Realizado";
            $_SESSION['modal_sucesso_mensagem'] = "Sua conta foi criada com sucesso!";
            $_SESSION['redirecionar_login'] = true;
            header('Location: ../views/cadastro.php');
            exit;

        } catch (ErroCadastroException $e) {
            if ($e->deveRedirecionarLogin()) {
                $_SESSION['redirecionar_login'] = true;
            }
            $_SESSION['modal_erro_titulo'] = $e->getTitulo();
            $_SESSION['modal_erro_mensagem'] = $e->getMessage();
            header('Location: ../views/cadastro.php');
            exit;
        }
    }


    // ──────────────────────────────────────────────────────────────
    // RECUPERAÇÃO DE SENHA
    // ──────────────────────────────────────────────────────────────
    public function validarRecuperacao(): void
    {
        ob_clean();
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');

        $usuario = $this->usuarioModel->validarUsuarioRecuperacao($email, $cpf);

        if ($usuario) {
            $this->usuarioModel->registrarLogSistema($usuario['id_usuario'], null, "RECUPERAÇÃO SENHA (Dados validados): $email");
            echo json_encode(['sucesso' => true]);
        } else {
            $this->usuarioModel->registrarLogSistema(null, null, "FALHA RECUPERAÇÃO SENHA (Dados inválidos): $email - CPF: $cpf");
            echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail ou CPF não conferem.']);
        }
        exit;
    }


    // ──────────────────────────────────────────────────────────────
    // ATUALIZAR SENHA
    // ──────────────────────────────────────────────────────────────
    public function atualizarSenha(): void
    {
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        $novaSenha = trim($_POST['novaSenha'] ?? '');

        // Pega os dados do usuário só para salvar no Log quem mudou a senha
        $usuarioLog = $this->usuarioModel->buscarUsuarioPorEmail($email);
        $uid = $usuarioLog['id_usuario'] ?? null;
        $fid = $usuarioLog['id_funcao'] ?? null;

        if (empty($novaSenha) || strlen($novaSenha) < 6) {
            $this->usuarioModel->registrarLogSistema($uid, $fid, "FALHA MUDANÇA SENHA (Senha muito curta): $email");
            echo json_encode(['sucesso' => false, 'mensagem' => 'Senha inválida.']);
            exit;
        }

        $resultado = $this->usuarioModel->atualizarSenha(
            $email,
            password_hash($novaSenha, PASSWORD_DEFAULT)
        );

        if ($resultado) {
            $this->usuarioModel->registrarLogSistema($uid, $fid, "SENHA ATUALIZADA COM SUCESSO");
            echo json_encode(['sucesso' => true]);
        } else {
            $this->usuarioModel->registrarLogSistema($uid, $fid, "FALHA MUDANÇA SENHA (Erro DB): $email");
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar banco.']);
        }
        exit;
    }


    // ──────────────────────────────────────────────────────────────
    // LOGOUT
    // ──────────────────────────────────────────────────────────────
    public function logout(): void
    {
        $this->iniciarSessao();
        
        if (isset($_SESSION['user_logado'])) {
            $id = $_SESSION['user_logado']['id'];
            $funcao = $_SESSION['user_logado']['id_funcao'];
            $this->usuarioModel->registrarLogSistema($id, $funcao, "REALIZOU LOGOUT");
        }

        session_unset();
        session_destroy();
        header('Location: ../views/index.php');
        exit;
    }


    // ══════════════════════════════════════════════════════════════
    // BARREIRA DE SEGURANÇA E INICIALIZAÇÃO DE AUDITORIA
    // ══════════════════════════════════════════════════════════════
    public static function verificarAcesso(?string $permissaoExigida = null): array
    {
        global $con; // Puxa a conexão do banco para avisar os Triggers

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redireciona se não logado
        if (!isset($_SESSION['user_logado'])) {
            header("Location: ../views/login.php");
            exit;
        }

        $usuario = $_SESSION['user_logado'];

        // Verifica permissão específica (RBAC)
        if ($permissaoExigida !== null && !in_array($permissaoExigida, $usuario['permissoes'] ?? [], true)) {
            // REGISTRO DE ACESSO NEGADO
            $id = (int) $usuario['id'];
            $funcao = (int) ($usuario['id_funcao'] ?? 8);
            $acao = "TENTOU ACESSAR ÁREA RESTRITA: " . $permissaoExigida;
            $con->query("INSERT INTO log_sistema (usuario_id, funcao_id, acao, entidade_afetada, id_entidade, data_log, hora_log) VALUES ($id, $funcao, '$acao', 'seguranca', 0, CURDATE(), CURTIME())");

            header("Location: ../views/sem-permissao.php");
            exit;
        }

        // AVISA OS TRIGGERS DO BANCO QUEM É O USUÁRIO
        $id_logado = (int) $usuario['id'];
        $funcao_logada = (int) ($usuario['id_funcao'] ?? 8);
        $con->query("SET @usuario_ativo = $id_logado, @funcao_ativa = $funcao_logada");

        return $usuario;
    }


    // ══════════════════════════════════════════════════════════════
    // MÉTODOS PRIVADOS — auxiliares internos
    // ══════════════════════════════════════════════════════════════

    private function iniciarSessao(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function buscarPermissoes(int $idUsuario): array
    {
        global $con;

        $sql = "
            SELECT DISTINCT p.nome_permissao
            FROM usuario u
            JOIN funcao_usuario   fu ON fu.usuario_id   = u.id_usuario
            JOIN funcao_permissao fp ON fp.funcao_id    = fu.funcao_id
            JOIN permissao        p  ON p.id_permissao  = fp.permissao_id
            WHERE u.id_usuario = ? 
        ";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $permissoes = [];
        while ($linha = $resultado->fetch_assoc()) {
            $permissoes[] = $linha['nome_permissao'];
        }

        return $permissoes;
    }

    private function processarFotoPerfil(): ?string
    {
        if (!isset($_FILES['foto_perfil']) || $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extensao, $extensoes_permitidas)) {
            throw new ErroCadastroException(
                "Erro de Arquivo",
                "Apenas imagens (JPG, PNG ou WEBP) são permitidas para a foto de perfil!"
            );
        }

        $diretorio = __DIR__ . '/../assets/uploads/fotos_perfil/';
        $nomeArquivo = uniqid('user_') . '.' . $extensao;
        $caminhoDestino = $diretorio . $nomeArquivo;

        if (!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminhoDestino)) {
            return null; 
        }

        return $nomeArquivo;
    }
}


// ══════════════════════════════════════════════════════════════════
// ROTEAMENTO
// ══════════════════════════════════════════════════════════════════
if (isset($_GET['acao'])) {

    $controller = new AutentController($con);

    match ($_GET['acao']) {
        'login' => $controller->login(),
        'cadastro' => $controller->cadastro(),
        'logout' => $controller->logout(),
        'validarRecuperacao' => $controller->validarRecuperacao(),
        'atualizarSenha' => $controller->atualizarSenha(),
        default => (function () {
                if (session_status() === PHP_SESSION_NONE)
                    session_start();
                $_SESSION['login_error'] = 'Ação Inválida!';
                header('Location: ../views/login.php');
                exit;
            })()
    };
}