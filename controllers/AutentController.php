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

        try {
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');

            if (empty($email) || empty($senha)) {
                throw new ErroLoginException("Erro de Validação", "Todos os campos devem ser preenchidos!");
            }

            if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/', $email)) {
                throw new ErroLoginException("Erro de Login", "Email ou senha inválidos!");
            }

            $usuario = $this->usuarioModel->buscarUsuarioPorEmail($email);

            if (!$usuario) {
                $_SESSION['redirecionar_cadastro'] = true;
                throw new ErroLoginException("Erro de Autenticação", "Usuário não cadastrado!");
            }

            if ($usuario['status_conta'] !== 'ativo') {
                throw new ErroLoginException("Conta Inativa", "Entre em contato com o suporte.");
            }

            if (!password_verify($senha, $usuario['senha_hash'])) {
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

        try {
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            $doc = trim($_POST['documento'] ?? '');

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

            // Lista de campos obrigatórios
            $camposObrigatorios = [
                'nome',
                'email',
                'senha_hash',
                'documento',
                'data_nascimento',
                'grau_academico',
                'nome_curso',
                'cidade',
                'estado',
                'pais',
            ];

            foreach ($camposObrigatorios as $campo) {
                if (empty(trim((string) $dados[$campo]))) {
                    throw new ErroCadastroException("Erro de Validação", "Todos os campos devem ser preenchidos!");
                }
            }

            if ($this->usuarioModel->verificarDados('email', $email)) {
                throw new ErroCadastroException(
                    "Erro de Cadastro",
                    "Os dados informados já possuem uma conta vinculada. Verifique suas informações!",
                    true
                );
            }

            if ($this->usuarioModel->verificarDados('documento', $doc)) {
                throw new ErroCadastroException(
                    "Erro de Cadastro",
                    "Os dados informados já possuem uma conta vinculada. Verifique suas informações!",
                    true
                );
            }

            // Processa upload da foto
            $dados['foto_perfil'] = $this->processarFotoPerfil();

            if (!$this->usuarioModel->cadastrarUsuario($dados)) {
                throw new ErroCadastroException("Erro de Cadastro", "Não foi possível concluir o cadastro. Tente novamente!");
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

        echo json_encode(
            $usuario
            ? ['sucesso' => true]
            : ['sucesso' => false, 'mensagem' => 'E-mail ou CPF não conferem.']
        );
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

        if (empty($novaSenha) || strlen($novaSenha) < 6) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Senha inválida.']);
            exit;
        }

        $resultado = $this->usuarioModel->atualizarSenha(
            $email,
            password_hash($novaSenha, PASSWORD_DEFAULT)
        );

        echo json_encode(
            $resultado
            ? ['sucesso' => true]
            : ['sucesso' => false, 'mensagem' => 'Erro ao atualizar banco.']
        );
        exit;
    }


    // ──────────────────────────────────────────────────────────────
    // LOGOUT
    // ──────────────────────────────────────────────────────────────
    public function logout(): void
    {
        $this->iniciarSessao();
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
            // ITEM 2 DA SUA LISTA: REGISTRO DE ACESSO NEGADO
            $id = (int) $usuario['id'];
            $funcao = (int) ($usuario['id_funcao'] ?? 8);
            $acao = "TENTOU ACESSAR: " . $permissaoExigida;
            $con->query("INSERT INTO log_sistema (usuario_id, funcao_id, acao, entidade_afetada, id_entidade, data_log, hora_log) VALUES ($id, $funcao, '$acao', 'seguranca', 0, CURDATE(), CURTIME())");

            header("Location: ../views/sem-permissao.php");
            exit;
        }

        // ITEM 3 DA SUA LISTA: AVISA OS TRIGGERS DO BANCO QUEM É O USUÁRIO
        $id_logado = (int) $usuario['id'];
        $funcao_logada = (int) ($usuario['id_funcao'] ?? 8);
        $con->query("SET @usuario_ativo = $id_logado, @funcao_ativa = $funcao_logada");

        return $usuario;
    }


    // ══════════════════════════════════════════════════════════════
    // MÉTODOS PRIVADOS — auxiliares internos
    // ══════════════════════════════════════════════════════════════

    // Inicia sessão com segurança
    private function iniciarSessao(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Retorna array com os nomes das permissões do usuário via RBAC (MySQLi)
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

        // Prepara consulta
        $stmt = $con->prepare($sql);

        // Passa o ID (inteiro)
        $stmt->bind_param("i", $idUsuario);

        // Executa
        $stmt->execute();

        // Pega resultado
        $resultado = $stmt->get_result();

        // Extrai nomes
        $permissoes = [];
        while ($linha = $resultado->fetch_assoc()) {
            $permissoes[] = $linha['nome_permissao'];
        }

        return $permissoes;
    }

    // Processa upload de foto e retorna o nome do arquivo (ou null)
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
            return null; // Falha no upload
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