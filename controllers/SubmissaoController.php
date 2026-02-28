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
        /* Verifica se o usuário está logado */
        if (!isset($_SESSION['user_logado']) || empty($_SESSION['user_logado']['id'])) {
            $_SESSION['modal_erro_titulo'] = "Acesso Restrito";
            $_SESSION['modal_erro_mensagem'] = "Você precisa realizar o login para enviar um arquivo!";
            $_SESSION['redirecionar_login'] = true;

            header('Location: ../views/submissao.php');
            exit;
        }

        /* Buscar evento com status ativo */
        $eventoAtivo = $this->submissaoModel->buscarEventoAtivo();

        if (!$eventoAtivo) {
            $_SESSION['modal_erro_titulo'] = "Evento Concluído";
            $_SESSION['modal_erro_mensagem'] = "Não existe um evento ativo para submissão no momento!";
            header('Location: ../views/submissao.php');
            exit;
        }

        /* Definir o caminho da pasta da edição atual do evento */
        $edicao = $eventoAtivo['id_evento'];
        $diretorioOriginal = __DIR__ . "/../assets/uploads/arquivos/edicao" . $edicao . "/";

        /* Criando a pasta da edição atual do evento caso não existir */
        if (!is_dir($diretorioOriginal)) {
            mkdir($diretorioOriginal, 0777, true);
        }

        /* Armazenando os valores dos campos em variáveis */
        $titulo = trim($_POST['titulo'] ?? '');
        $resumo = trim($_POST['resumo'] ?? '');
        $palavras_chave = trim($_POST['palavras_chave'] ?? '');

        /* Armazenando os valores dos coautores */
        $coautoresNomes = $_POST['coautor_nome'] ?? [];
        $coautoresEmails = $_POST['coautor_email'] ?? [];
        $coautoresInsts = $_POST['coautor_inst'] ?? [];

        /* Verifica se os campos estão vazios */
        if (empty($titulo) || empty($resumo) || empty($palavras_chave) || empty($_FILES['arquivo']['name'])) {
            $this->redirecionarErro("Campos Obrigatórios", "Título, Resumo, Palavras-chave e o Arquivo são obrigatórios!");
        }

        /* Validação de Coautores */
        $padraoEmail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'; //Armazenando o formato do email padrão
        $coautoresValidos = [];


        for ($i = 0; $i < count($coautoresNomes); $i++) {
            $coautorNome = trim($coautoresNomes[$i] ?? '');
            $coautorEmail = trim($coautoresEmails[$i] ?? '');
            $coautorInst = trim($coautoresInsts[$i] ?? '');

            $campoPreenchido = (!empty($coautorNome) || !empty($coautorEmail) || !empty($coautorInst));

            if ($campoPreenchido) {
                // 1. Se ele preencheu um campo, os outros campos do mesmo bloco (Coautores) tornam-se obrigatórios
                if (empty($coautorNome) || empty($coautorEmail) || empty($coautorInst)) {
                    $this->redirecionarErro("Campos Obrigatórios", "Você adicionou um ou mais coautores, mas deixou campos vazios.");
                }

                // 2. Só agora validamos o formato do e-mail (agora que sabemos que não está vazio)
                if (!preg_match($padraoEmail, $coautorEmail)) {
                    $this->redirecionarErro("E-mail Inválido", "O e-mail informado para o coautor não é válido!");
                }

                $coautoresValidos[] = ['nome' => $coautorNome, 'email' => $coautorEmail, 'inst' => $coautorInst];
            }
        }

        /* Verifica se tem mais de 5 coautores*/
        if (count($coautoresValidos) > 5) {
            $this->redirecionarErro("Limite de Coautores", "O limite máximo é de 5 coautores.");
        }

        /* Caso o Arquivo foi enviado, ele é armazenado na variável*/
        $arquivo = $_FILES['arquivo'];

        /* Separando dos dados do arquivo enviado */
        $nomeOriginal = $arquivo['name']; // Nome orignal do arquivo
        $tipoMime = $arquivo['type']; // Identificador de extensão do Arquivo
        $tamanho = $arquivo['size']; // Tamanho do Arquivo

        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $extensoesPermitidas = ['pdf', 'docx'];

        $mimesPermitidos = [
            'application/pdf', //Mime do PDF
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' //Mime do DOCX
        ];

        /* Verifica se o arquivo foi enviado */
        if (!isset($arquivo)) {
            $this->redirecionarErro("Arquivo Ausente", "Por favor, selecione um arquivo PDF ou DOCX para enviar!");
        }

        /* Verifica se o Mime existe dentro do arquivo */
        if (!in_array($extensao, $extensoesPermitidas) || !in_array($tipoMime, $mimesPermitidos)) {
            $this->redirecionarErro("Arquivo Inválido", "Apenas PDF ou DOCX são aceitos.");
        }

        /* Verifica se o arquivo tem mais de 10MB */
        if ($tamanho > 10 * 1024 * 1024) {
            $this->redirecionarErro("Tamanho do Arquivo Inválido", "O arquivo excede o limite de 10MB.");
        }

        /* Criando nome único para o arquivo */
        $novoNome = uniqid('trab_') . '_' . time() . '.' . $extensao; // a função time diminui a chance de nomes iguais serem enviados ao mesmo tempo, pois adiciona o tempo exato do envio
        $caminhoFinal = $diretorioOriginal . $novoNome;

        if (move_uploaded_file($arquivo['tmp_name'], $caminhoFinal)) {
            $id_usuario = $_SESSION['user_logado']['id'];
            $id_vinculo = $this->submissaoModel->promoverParaAutor($id_usuario);

            $sucessoEnvio = false;

            if ($id_vinculo) {
                $dadosSubmissao = [
                    'evento_id' => $edicao,
                    'funcao_usuario_id' => $id_vinculo,
                    'titulo' => $titulo,
                    'resumo' => $resumo,
                    'palavras_chave' => $palavras_chave,
                    'caminho_arquivo' => "edicao_" . $edicao . "/" . $novoNome,
                    'data_envio' => date('Y-m-d'),
                    'hora_envio' => date('H:i:s')
                ];

                /* Cadastrando Submissão */
                $enviarSubmissao = $this->submissaoModel->cadastrarSubmissao($dadosSubmissao);

                if ($enviarSubmissao) {
                    foreach ($coautoresValidos as $coautor) {
                        $this->submissaoModel->cadastrarCoautor($enviarSubmissao, $coautor);
                    }
                    $sucessoEnvio = true;
                }
            }

            if ($sucessoEnvio) {
                $_SESSION['modal_sucesso_titulo'] = "Submissão Realizada!";
                $_SESSION['modal_sucesso_mensagem'] = "Seu trabalho foi enviado com sucesso.";
                header('Location: ../views/arquivos.php');
                exit;
            } else {
                // Se chegou aqui, qualquer erro (vínculo ou submissão) apaga o arquivo
                if (file_exists($caminhoFinal))
                    unlink($caminhoFinal);
                $this->redirecionarErro("Erro no Cadastro", "Não foi possível salvar os dados no sistema.");
            }
        }
    }

    // Função de Erro
    private function redirecionarErro($titulo, $mensagem)
    {
        $_SESSION['modal_erro_titulo'] = $titulo;
        $_SESSION['modal_erro_mensagem'] = $mensagem;
        header('Location: ../views/submissao.php');
        exit;
    }

}

$controller = new SubmissaoController($con);
$controller->enviarArquivo();
?>