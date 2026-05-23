<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class AutentController
{
    private UsuarioModel $usuarioModel;

    public function __construct($con)
    {
        $this->usuarioModel = new UsuarioModel($con);
    }
    
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if (empty($email) || empty($senha)) {
            $this->erroLogin("Erro de Validação", "Todos os campos devem ser preenchidos!");
        }

        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/', $email)) {
            $this->erroLogin("Erro de Login", "Email ou senha inválidos!");
        }

        $usuario = $this->usuarioModel->buscarUsuarioPorEmail($email);

        /* Se não existir o usuário, gera LOG DE FALHA e bloqueia */
        if (!$usuario) {
            $_SESSION['redirecionar_cadastro'] = true;
            // LOG: Falha por usuário não cadastrado
            $this->usuarioModel->registrarLog(null, null, "FALHA LOGIN (Usuário não cadastrado): " . substr($email, 0, 20), null, 'autenticacao', 0);
            $this->erroLogin("Erro de Autenticação", "Usuário não cadastrado!");
        }

        if ($usuario['status_conta'] !== 'ativo') {
            $this->erroLogin("Conta Inativa", "Entre em contato com o suporte.");
        }

        /* Se a senha estiver errada, gera LOG DE FALHA e bloqueia */
        if (!password_verify($senha, $usuario['senha_hash'])) {
            // LOG: Falha por senha incorreta
            $this->usuarioModel->registrarLog($usuario['id_usuario'], $usuario['id_funcao'], "FALHA LOGIN (Senha incorreta): " . substr($email, 0, 20), null, 'autenticacao', 0);
            $this->erroLogin("Erro de Login", "Email ou senha incorretos!");
        }

        // BLINDAGEM 1: Gera um novo ID de sessão limpo
        session_regenerate_id(true);

        $_SESSION['user_logado'] = [
            'id' => $usuario['id_usuario'],
            'nome' => $usuario['nome_usuario'],
            'email' => $usuario['email'],
            'foto' => $usuario['foto_perfil'] ?? null,
            'id_funcao' => $usuario['id_funcao'],
            'nome_funcao' => $usuario['nome_funcao']
        ];

        // LOG: Sucesso no Login!
        $this->usuarioModel->registrarLog($usuario['id_usuario'], $usuario['id_funcao'], "LOGIN BEM SUCEDIDO", null, 'autenticacao', 0);

        // REDIRECIONAMENTO ALTERADO PARA O PAINEL
        header('Location: ../views/painel.php');
        exit;
    }
    
    public function cadastro(): void
    {
        $email = trim($_POST['email'] ?? '');  
        $senha = trim($_POST['senha'] ?? ''); 
        $doc = trim($_POST['documento'] ?? ''); 

        $dados = [
            'nome' => trim($_POST['nome_usuario'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'senha_hash' => !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : '',
            'documento' => trim($_POST['documento'] ?? ''),
            'data_nascimento' => implode('-', array_reverse(explode('/', $_POST['data_nascimento'] ?? ''))),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'instagram' => trim($_POST['instagram'] ?? ''),
            'grau_academico' => trim($_POST['grau_academico'] ?? ''),
            'nome_curso' => trim($_POST['nome_curso'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'estado' => trim($_POST['estado'] ?? ''),
            'pais' => trim($_POST['pais'] ?? ''),
            'foto_perfil' => null
        ];

        if (
            trim(empty($dados['nome'])) || trim(empty($dados['email'])) || trim(empty($dados['senha_hash'])) ||
            trim(empty($dados['documento'])) || trim(empty($dados['data_nascimento'])) ||
            trim(empty($dados['grau_academico'])) || trim(empty($dados['nome_curso'])) ||
            trim(empty($dados['cidade'])) || trim(empty($dados['estado'])) || trim(empty($dados['pais']))
        ) {
            $this->erroCadastro("Erro de Validação", "Todos os campos devem ser preenchidos!");
        }

        $emailExiste = $this->usuarioModel->verificarDados('email', $email);
        $docExiste = $this->usuarioModel->verificarDados('documento', $doc);

        /* Se o email ou CPF for duplicado, gera LOG DE FALHA e bloqueia */
        if ($emailExiste || $docExiste) {
            $_SESSION['redirecionar_login'] = true;
            $motivo = $emailExiste ? "E-mail duplicado" : "Documento duplicado";
            $valorFalho = $emailExiste ? $email : $doc;
            
            // LOG: Falha de Cadastro Duplicado
            $this->usuarioModel->registrarLog(null, null, "FALHA CADASTRO ($motivo): " . substr($valorFalho, 0, 20) . "...", null, 'autenticacao', 0);
            
            $this->erroCadastro("Erro de Cadastro", "Os dados informados já possuem uma conta vinculada. Verifique suas informações!");
        }

        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extensao, $extensoes_permitidas)) {
                $this->erroCadastro("Erro de Arquivo", "Apenas imagens (JPG, PNG ou WEBP) são permitidas para a foto de perfil!");
            }

            $diretorioUploads = __DIR__ . '/../assets/uploads/fotos_perfil/'; 
            $nomeArquivo = uniqid('user_') . '.' . $extensao; 
            $caminhoDestino = $diretorioUploads . $nomeArquivo;

            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminhoDestino)) {
                $dados['foto_perfil'] = $nomeArquivo; 
            } else {
                $dados['foto_perfil'] = null; 
            }
        }

        $novoUsuarioId = $this->usuarioModel->cadastrarUsuario($dados);

        if (!$novoUsuarioId) {
            $this->erroCadastro("Erro de Cadastro", "Não foi possível concluir o cadastro, Tente novamente!");
        } else {
            $this->usuarioModel->registrarLog($novoUsuarioId, 8, 'CADASTRO BEM SUCEDIDO', null, 'autenticacao', 0);
            
            $this->sucessoCadastro("Cadastro Realizado", "Sua conta foi criada com sucesso!");
            header('Location: ../views/login.php');
            exit;
        }
    }

    public function validarRecuperacao(): void
    {
        ob_clean();
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');

        $usuario = $this->usuarioModel->validarUsuarioRecuperacao($email, $cpf);

        if ($usuario) {
            // LOG DE TENTATIVA BEM SUCEDIDA (Chegou na etapa de inserir nova senha)
            $this->usuarioModel->registrarLog($usuario['id_usuario'], $usuario['funcao_id'], "TENTATIVA RECUPERACAO SENHA", null, 'autenticacao', 0);
            echo json_encode(['sucesso' => true]);
        } else {
            // LOG DE TENTATIVA FRACASSADA
            $this->usuarioModel->registrarLog(null, null, "FALHA RECUPERACAO SENHA (Dados invalidos): " . substr($email, 0, 20), null, 'autenticacao', 0);
            echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail ou CPF não conferem.']);
        }
        exit;
    }

    public function atualizarSenha(): void
    {
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        $novaSenha = trim($_POST['novaSenha'] ?? '');

        if (empty($novaSenha) || strlen($novaSenha) < 6) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Senha inválida.']);
            exit;
        }

        // Busca o usuário atual para poder atrelar o log corretamente ao ID dele
        $usuario = $this->usuarioModel->buscarUsuarioPorEmail($email);

        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $resultado = $this->usuarioModel->atualizarSenha($email, $senhaHash);

        if ($resultado) {
            if ($usuario) {
                // LOG DA AÇÃO CONCLUIDA!
                $this->usuarioModel->registrarLog($usuario['id_usuario'], $usuario['id_funcao'], "SENHA ALTERADA", null, 'autenticacao', 0);
            }
            echo json_encode(['sucesso' => true]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar banco.']);
        }
        exit;
    }

    public function logout(): void
    {
        // LOG: Registra o Logout antes de destruir a sessão
        if (isset($_SESSION['user_logado'])) {
            $id = $_SESSION['user_logado']['id'];
            $id_funcao = $_SESSION['user_logado']['id_funcao'];
            $this->usuarioModel->registrarLog($id, $id_funcao, 'REALIZOU LOGOUT', null, 'autenticacao', 0);
        }

        session_unset();
        session_destroy();
        header('Location: ../views/index.php');
    }

    private function erroLogin(string $titulo, string $mensagem): void
    {
        $_SESSION['modal_erro_titulo'] = $titulo;
        $_SESSION['modal_erro_mensagem'] = $mensagem;
        header('Location: ../views/login.php');
        exit;
    }

    private function sucessoCadastro(string $titulo, string $mensagem): void
    {
        $_SESSION['modal_sucesso_titulo'] = $titulo;
        $_SESSION['modal_sucesso_mensagem'] = $mensagem;
        header('Location: ../views/cadastro.php');
        exit;
    }

    private function erroCadastro(string $titulo, string $mensagem): void
    {
        $_SESSION['modal_erro_titulo'] = $titulo;
        $_SESSION['modal_erro_mensagem'] = $mensagem;
        header('Location: ../views/cadastro.php');
        exit;
    }

    public static function verificarAcesso(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_logado'])) {
            header("Location: ../views/login.php");
            exit;
        }

        return $_SESSION['user_logado'];
    }
}

if (isset($_GET['acao'])) {

    $acao = $_GET['acao'];
    $controller = new AutentController($con);

    if ($acao === 'login') {
        $controller->login();
    } else if ($acao === 'cadastro') {
        $controller->cadastro();
    } else if ($acao === 'logout') {
        $controller->logout();
    } else if ($acao === 'validarRecuperacao') {
        $controller->validarRecuperacao();
    } else if ($acao === 'atualizarSenha') {
        $controller->atualizarSenha();
    } else {
        $_SESSION['login_error'] = 'Ação Inválida!';
        header('Location: ../views/login.php');
        exit;
    }
}
?>