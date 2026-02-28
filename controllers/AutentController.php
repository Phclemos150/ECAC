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
    /* Classe de login */
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        /* Verifica se os campos estão vazios */
        if (empty($email) || empty($senha)){
            $this->erroLogin("Erro de Validação", "Todos os campos devem ser preenchidos!");
        }

        /* Verifica se o campo email está correto */
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/', $email)) {
            $this->erroLogin("Erro de Login", "Email ou senha inválidos!");
        }

        /* Consulta o DB e verifica se o usuario existe pelo email */
        $usuario = $this->usuarioModel->buscarUsuarioPorEmail($email);

        /* Se o usuário não estiver cadastrado, inicia a sessão de erro de login */
        if (!$usuario) {
            $_SESSION['redirecionar_cadastro'] = true;
            $this->erroLogin("Erro de Autenticação", "Usuário não cadastrado!");
        }

        /* Verifica o status da conta */
        if ($usuario['status_conta'] !== 'ativo') {
            $this->erroLogin("Conta Inativa", "Entre em contato com o suporte.");
        }

        /* Verifica a criptografia da senha */
        if (!password_verify($senha, $usuario['senha_hash'])) {
            $this->erroLogin("Erro de Login", "Email ou senha incorretos!");
        }

        /* Se o Usuário estiver cadastrado, inicia a sessão de logado */
        $_SESSION['user_logado'] = [
            'id' => $usuario['id_usuario'],
            'nome' => $usuario['nome_usuario'],
            'email' => $usuario['email'],
            'foto' => $usuario['foto_perfil'] ?? null
        ];

        header('Location: ../views/index.php');
        exit;
    }
    /* Classe de Cadastro */
    public function cadastro(): void
    {
        $email = trim($_POST['email'] ?? '');  // Armazena o valor do email para tratamento de duplicidade no cadastro
        $senha = trim($_POST['senha'] ?? ''); // Armazena para testar a criptografia da senha e tratar senha  criptografada correta
        $doc = trim($_POST['documento'] ?? ''); // Armazena o valor do documento para tratamento de duplicidade no cadastro

        $dados = [
            'nome' => trim($_POST['nome_usuario'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'senha_hash' => !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : '',
            'documento' => trim($_POST['documento'] ?? ''),
            'data_nascimento' => trim($_POST['data_nascimento'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'instagram' => trim($_POST['instagram'] ?? ''),
            'nivel_escolaridade' => trim($_POST['nivel_escolaridade'] ?? ''),
            'nome_curso' => trim($_POST['nome_curso'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'estado' => trim($_POST['estado'] ?? ''),
            'pais' => trim($_POST['pais'] ?? ''),
            'foto_perfil' => null
        ];

        /* Verifica se os campos estão vazios */
        if (trim(empty($dados['nome'])) || trim(empty($dados['email'])) || trim(empty($dados['senha_hash'])) ||
            trim(empty($dados['documento'])) || trim(empty($dados['data_nascimento'])) ||
            trim(empty($dados['nivel_escolaridade'])) || trim(empty($dados['nome_curso'])) ||
            trim(empty($dados['cidade'])) || trim(empty($dados['estado'])) || trim(empty($dados['pais']))) {
            $this->erroCadastro("Erro de Validação", "Todos os campos devem ser preenchidos!");
        }

        /* 1. Verifica se o e-mail ou o cpf já está cadastrado */
        $emailExiste = $this->usuarioModel->verificarDados('email', $email);
        $docExiste = $this->usuarioModel->verificarDados('documento', $doc);

        if ($emailExiste || $docExiste) {
            $_SESSION['redirecionar_login'] = true;
            $this->erroCadastro("Erro de Cadastro", "Os dados informados já possuem uma conta vinculada. Verifique suas informações!");
        }

        /* Valida o upload da foto de perfil */
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $diretorioUploads = __DIR__ . '/../assets/uploads/fotos_perfil/'; // Armazena o caminho do diretório uploads/fotos_perfil

            $extensao = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION); // Organiza o nome do arquivo, mantendo a extensão
            $nomeArquivo = uniqid('user_') . '.' . $extensao; // uniqid() gera um id único caso haja fotos com o mesmo nome, nesse caso adiciona o user_ + um codigo aleatório

            /* Caminho completo do arquivo */
            $caminhoDestino = $diretorioUploads . $nomeArquivo;

            /* Verifica se o arquivo  */
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminhoDestino)) {
                $dados['foto_perfil'] = $nomeArquivo; // Armazena o caminho da imagem mas não o arquivo em si 
            } else {
                $dados['foto_perfil'] = null; // Caso o usuário não envie uma foto, ele armazena null
            }
        }

        /* Cadastra o usuário no banco */
        $resultado = $this->usuarioModel->cadastrarUsuario($dados);

        if (!$resultado) {
            $this->erroCadastro("Erro de Cadastro", "Não foi possível concluir o cadastro, Tente novamente!");
        } else {
            $this->sucessoCadastro("Cadastro Realizado", "Sua conta foi criada com sucesso!");
            header('Location: ../views/login.php');
            exit;
        }
    }

    /* Classe de Logout(finalizar sessão) */
    public function logout(): void
    {
        session_unset();
        session_destroy();
        /* Redirecionando para a página principal após finalizar a sessão */
        header('Location: ../views/index.php');
    }

    private function erroLogin(string $titulo, string $mensagem): void
    {
        // Mensagem de erro específica para o login do usuário
        $_SESSION['modal_erro_titulo'] = $titulo;
        $_SESSION['modal_erro_mensagem'] = $mensagem;
        header('Location: ../views/login.php');
        exit;
    }

    private function sucessoCadastro(string $titulo, string $mensagem): void
    {
        $_SESSION['modal_sucesso_titulo'] = $titulo;
        $_SESSION['modal_sucesso_mensagem'] = $mensagem;
        // Redireciona para o login, onde o modal será exibido
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
}

/* Determinar a Ação da Autenticação */
$acao = $_GET['acao'] ?? 'login'; // Se a variável ação não receber parametros, vai assumir que está sendo realizado o login

$controller = new AutentController($con);

if ($acao === 'login') {
    $controller->login(); // Se tiver o parametro login, então chama a função de logar
} else if ($acao === 'cadastro') {
    $controller->cadastro(); // Se tiver o parametro cadastro, então chama a função de cadastrar
} else if ($acao === 'logout') {
    $controller->logout(); // Se tiver o parametro logouy, então chama a função de deslogar
} else {
    $_SESSION['login_error'] = 'Ação Inválida!'; // Caso houver outro parametro sendo enviado, retorna para o login com uma mensagem de erro
    header('Location: ../views/login.php');
    exit;
}

?>