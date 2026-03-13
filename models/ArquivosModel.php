<?php

class ArquivosModel
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    /* Função de Buscar de todos os arquivos submetidos */
    public function listarArquivos(): array
    {
        $arquivos = [];

        /* Sql buscando todos os arquivos submetidos com o evento, usuario (autor) associado */
        $sql = "SELECT s.*, e.titulo AS evento_titulo, e.data_evento, u.nome_usuario AS autor_nome FROM submissao s 
        INNER JOIN evento e ON e.id_evento = s.evento_id 
        INNER JOIN funcao_usuario fu ON s.funcao_usuario_id = fu.id_funcao_usuario 
        INNER JOIN usuario u ON fu.usuario_id = u.id_usuario 
        ORDER BY e.data_evento DESC, s.data_envio DESC, s.hora_envio DESC";

        $result = $this->con->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Para buscar se existem coautores com base no id
                $id_submissao = $row['id_submissao'];
                $sqlCoautores = "SELECT nome_coautor, instituicao FROM coautores where submissao_id = $id_submissao";
                $resultCoautores = $this->con->query($sqlCoautores);

                $coautores = [];
                while ($co = $resultCoautores->fetch_assoc()) {
                    $coautores[] = $co; // Armazena o nome_coautor e instituição
                }

                $row['coautores'] = $coautores; // Armazena o vetor coautores na linha do arquivo
                $arquivos[] = $row;
            }
        }

        return $arquivos;

    }
}

?>