<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/SubmissaoModel.php';

class SubmissaoController
{
    private SubmissaoModel $submissaoModel;

    public function __construct($con)
    {
        $this->submissaoModel = new SubmissaoModel($con);
    }

    public function enviarArquivo(): void
    {
        //Verifica se o usuário está logado
        if (!isset($_SESSION['user_logado']) || empty($_SESSION['user_logado']['id'])) {
            $_SESSION['modal_erro_titulo'] = "Acesso Restrito";
            $_SESSION['modal_erro_mensagem'] = "Você precisa realizar o login para enviar um arquivo!";
            $_SESSION['redirecionar_login'] = true;

            header('Location: ../views/submissao.php');
            exit;
        }

        /* Armazenando os valores dos campos em variáveis */
        $titulo = trim($_POST['titulo'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $resumo = trim($_POST['resumo'] ?? '');
        $palavras_chave = trim($_POST['palavras_chave'] ?? '');

        /* Armazenando os valores dos coautores */
        $coautoresNomes = $_POST['coautor_nome'] ?? [];
        $coautoresEmails = $_POST['coautor_email'] ?? [];
        $coautoresInsts = $_POST['coautor_inst'] ?? [];

        /* Verifica se os campos estão vazios */
        if (empty($titulo) || empty($autor) || empty($resumo) || empty($palavras_chave)) {
            $_SESSION['modal_erro_titulo'] = "Campos Obrigatórios";
            $_SESSION['modal_erro_mensagem'] = "Título, Autor e Resumo precisam ser preenchidos!";
            header('Location: ../views/submissao.php');
            exit;
        }

        /* Armazenando o formato do email padrão */
        $padraoEmail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        /* Contagem de coautores exibidos*/
        $totalCamposExibidos = count($coautoresNomes);
        $contagemCampos = 0;

        for ($i = 0; $i < $totalCamposExibidos; $i++) {
            $coautorNome = trim($coautoresNomes[$i] ?? '');
            $coautorEmail = trim($coautoresEmails[$i] ?? '');
            $coautorInst = trim($coautoresInsts[$i] ?? '');

            $campoPreenchido = (!empty($coautorNome) || !empty($coautorEmail) || !empty($coautorInst));

            if ($campoPreenchido) {
                // 1. Se ele preencheu um campo, os outros dois do mesmo bloco tornam-se obrigatórios
                if (empty($coautorNome) || empty($coautorEmail) || empty($coautorInst)) {
                    $_SESSION['modal_erro_titulo'] = "Campos Obrigatórios";
                    $_SESSION['modal_erro_mensagem'] = "Você adicionou um ou mais coautores, mas deixou campos vazios!";
                    header('Location: ../views/submissao.php');
                    exit;
                }

                // 2. Só agora validamos o formato do e-mail (agora que sabemos que não está vazio)
                if (!preg_match($padraoEmail, $coautorEmail)) {
                    $_SESSION['modal_erro_titulo'] = "E-mail Inválido";
                    $_SESSION['modal_erro_mensagem'] = "O e-mail informado para o coautor não é válido!";
                    header('Location: ../views/submissao.php');
                    exit;
                }

                // Se passou pelas validações, incrementamos a contagem de coautores válidos
                $contagemCampos++;
            }
        }

        /* Verifica se tem mais de 5 coautores*/
        if ($contagemCampos > 5) {
            $_SESSION['modal_erro_titulo'] = "Limite de Coautores";
            $_SESSION['modal_erro_mensagem'] = "Um trabalho acadêmico pode ter no máximo 5 coautores.";
            header('Location: ../views/submissao.php');
            exit;
        }

        /* Verifica se o arquivo foi enviado */
        if (!isset($_FILES['arquivo'])) {
            $_SESSION['modal_erro_titulo'] = "Arquivo Ausente";
            $_SESSION['modal_erro_mensagem'] = "Por favor, selecione um arquivo PDF ou DOCX para enviar.";
            header('Location: ../views/submissao.php');
            exit;
        }

        /* Caso o Arquivo foi enviado, ele é armazenado na variável*/
        $arquivo = $_FILES['arquivo'];

        /* Separando dos dados do arquivo enviado */
        $nomeOriginal = $arquivo['name']; // Nome orignal do arquivo
        $tipoMime = $arquivo['type']; // Identificador de extensão do Arquivo
        $tamanho = $arquivo['size']; // Tamanho do Arquivo
        $caminhoTemp = $arquivo['tmp_name']; // Caminho de armazenamento temporário

        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $extensoesPermitidas = ['pdf', 'docx'];

        $mimesPermitidos = [
            'application/pdf', //Mime do PDF
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' //Mime do DOCX
        ];

        /* Verifica se o Mime existe dentro do arquivo */
        if (!in_array($extensao, $extensoesPermitidas) || !in_array($tipoMime, $mimesPermitidos)) {
            $_SESSION['modal_erro_titulo'] = "Arquivo Inválido";
            $_SESSION['modal_erro_mensagem'] = "Apenas arquivos PDF ou DOCX são aceitos.";
            header('Location: ../views/submissao.php');
            exit;
        }

        /* Verifica se o arquivo tem mais de 10MB */
        if ($tamanho > 10 * 1024 * 1024) {
            $_SESSION['modal_erro_titulo'] = "Arquivo muito grande";
            $_SESSION['modal_erro_mensagem'] = "O arquivo excede o limite de 10MB.";
            header('Location: ../views/submissao.php');
            exit;
        }
    }
}

$controller = new SubmissaoController($con);
$controller->enviarArquivo();
?>