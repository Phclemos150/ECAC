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
        if (empty($email) || empty($senha)) {
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

        // BLINDAGEM 1: Gera um novo ID de sessão limpo para evitar roubo (Session Hijacking)
        session_regenerate_id(true);

        /* * NOVIDADE 1: Guardando as funções na sessão!
         * Agora adicionamos o id_funcao e o nome_funcao que vieram do banco
         */
        $_SESSION['user_logado'] = [
            'id' => $usuario['id_usuario'],
            'nome' => $usuario['nome_usuario'],
            'email' => $usuario['email'],
            'foto' => $usuario['foto_perfil'] ?? null,
            'id_funcao' => $usuario['id_funcao'],
            'nome_funcao' => $usuario['nome_funcao']
        ];

        /* REDIRECIONAMENTO ÚNICO: Todos vão para a tela inicial ao logar */
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

        /* Verifica se os campos estão vazios */
        if (
            trim(empty($dados['nome'])) || trim(empty($dados['email'])) || trim(empty($dados['senha_hash'])) ||
            trim(empty($dados['documento'])) || trim(empty($dados['data_nascimento'])) ||
            trim(empty($dados['grau_academico'])) || trim(empty($dados['nome_curso'])) ||
            trim(empty($dados['cidade'])) || trim(empty($dados['estado'])) || trim(empty($dados['pais']))
        ) {
            $this->erroCadastro("Erro de Validação", "Todos os campos devem ser preenchidos!");
        }

        /* 1. Verifica se o e-mail ou o cpf já está cadastrado */
        $emailExiste = $this->usuarioModel->verificarDados('email', $email);
        $docExiste = $this->usuarioModel->verificarDados('documento', $doc);

        if ($emailExiste || $docExiste) {
            $_SESSION['redirecionar_login'] = true;
            $this->erroCadastro("Erro de Cadastro", "Os dados informados já possuem uma conta vinculada. Verifique suas informações!");
        }

        /* Valida o upload da foto de perfil (AGORA COM BLINDAGEM) */
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {

            // BLINDAGEM 2: Verifica se o arquivo realmente é uma imagem segura
            $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extensao, $extensoes_permitidas)) {
                $this->erroCadastro("Erro de Arquivo", "Apenas imagens (JPG, PNG ou WEBP) são permitidas para a foto de perfil!");
            }

            $diretorioUploads = __DIR__ . '/../assets/uploads/fotos_perfil/'; // Armazena o caminho do diretório uploads/fotos_perfil

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

    /* ==========================================
     * BARREIRA DE SEGURANÇA (O Guarda-Costas)
     * Verifica se a pessoa logou antes de deixar ver a página
     * ========================================== */
    public static function verificarAcesso(): array
    {
        // 1. Verifica se a sessão do PHP já começou. Se não começou, ele inicia.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Olha para a "mochila" (Sessão). Se NÃO existir o 'user_logado' lá dentro...
        if (!isset($_SESSION['user_logado'])) {
            // Chuta a pessoa de volta para a tela de login
            header("Location: ../views/login.php");
            exit;
        }

        // 3. Se a pessoa estiver logada certinho, devolve os dados dela para a página usar (nome, função, foto)
        return $_SESSION['user_logado'];
    }
}

/* ==========================================
 * ROTEAMENTO INTELIGENTE
 * Só tenta executar rotas se a página pedir explicitamente (?acao=...)
 * Isso impede que o Controller tente logar as pessoas acidentalmente   
 * ========================================== */
if (isset($_GET['acao'])) {

    $acao = $_GET['acao'];
    $controller = new AutentController($con);

    if ($acao === 'login') {
        $controller->login();
    } else if ($acao === 'cadastro') {
        $controller->cadastro();
    } else if ($acao === 'logout') {
        $controller->logout();
    } else {
        $_SESSION['login_error'] = 'Ação Inválida!';
        header('Location: ../views/login.php');
        exit;
    }
}

?>