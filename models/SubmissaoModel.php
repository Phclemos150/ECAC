<?php
class SubmissaoModel
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    /* Função de consulta por evento ativo */
    public function buscarEventoAtivo(): ?array
    {
        $sql = "SELECT id_evento FROM evento WHERE status_evento = 'ativo' LIMIT 1";
        $result = $this->con->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    /* Função para alterar função do usuário para autor */
    public function promoverParaAutor(int $id_usuario): ?int
    {
        /* Consultando o ID da função 'Autor' */
        $sqlFuncao = "SELECT id_funcao FROM funcao WHERE nome_funcao = 'Autor' LIMIT 1";
        $resultFuncao = $this->con->query($sqlFuncao);

        if (!$resultFuncao || $resultFuncao->num_rows === 0) {
            return null; // Caso der erro de consulta de autores, retorna null
        }

        $id_funcao_autor = $resultFuncao->fetch_assoc()["id_funcao"];

        /* Verifica se a função do usuário é 'Autor' */
        $sqlCheckVinculo = "SELECT id_funcao_usuario FROM funcao_usuario WHERE usuario_id = ? AND funcao_id = ? LIMIT 1";
        $stmt = $this->con->prepare($sqlCheckVinculo);
        $stmt->bind_param("ii", $id_usuario, $id_funcao_autor);
        $stmt->execute();
        $resultVinculo = $stmt->get_result();

        /* Se a função do usuario for autor não será alterado nada */
        if ($resultVinculo->num_rows > 0) {
            $id_vinculo = $resultVinculo->fetch_assoc()["id_funcao_usuario"];
            $stmt->close();
            return $id_vinculo;
        }
        $stmt->close();

        /* Se a função não for de autor, a função é alterada */
        $sqlUpdateVinculo = "UPDATE funcao_usuario SET funcao_id = ? WHERE usuario_id = ?";
        $stmtUp = $this->con->prepare($sqlUpdateVinculo);
        $stmtUp->bind_param("ii", $id_funcao_autor, $id_usuario);

        if ($stmtUp->execute()) {
            $stmtUp->close();
            /* Busca o id_funcao_usuario a função de autor (alterada ou já existente) para usar no envio da submissao */
            $sqlGetId = "SELECT id_funcao_usuario FROM funcao_usuario WHERE usuario_id = ? LIMIT 1";
            $stmtId = $this->con->prepare($sqlGetId);
            $stmtId->bind_param("i", $id_usuario);
            $stmtId->execute();
            $resultId = $stmtId->get_result()->fetch_assoc();
            $stmtId->close();
            return (int) $resultId["id_funcao_usuario"];
        }

        return null;  // Caso der erro de promoção de função para autor, retorna null
    }

    /* Cadastra os dados da Submissão de Trabalhos */
    public function cadastrarSubmissao(array $dados): ?int
    {
        $sql = "INSERT INTO submissao (evento_id, funcao_usuario_id, titulo, resumo, palavras_chave, caminho_arquivo, data_envio, hora_envio, status_arquivo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'enviado')";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param(
            "iissssss",
            $dados["evento_id"],
            $dados["funcao_usuario_id"],
            $dados["titulo"],
            $dados["resumo"],
            $dados["palavras_chave"],
            $dados["caminho_arquivo"],
            $dados["data_envio"],
            $dados["hora_envio"]
        );

        if ($stmt->execute()) {
            $id_submissao = $this->con->insert_id; //armazena o id da submissão para retornar e os coautores cadastrados serem vinculados a submissao
            $stmt->close();
            return $id_submissao;
        }

        $stmt->close();
        return null; // Caso der erro de cadastro, retorna null
    }

    public function cadastrarCoautor(int $submissao_id, array $coautor): bool
    {
        $sql = "INSERT INTO coautores (submissao_id, nome_coautor, email, instituicao
            ) VALUES (?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("isss",
            $submissao_id,
            $coautor["nome"],
            $coautor["email"],
            $coautor["inst"]
        );

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

}
?>