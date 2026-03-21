<?php

class ArquivosModel
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    /* Função de Buscar Coautores pelo id da submissão */
    private function buscarCoautores(int $id_submissao): array
    {
        $sql = "SELECT nome_coautor, instituicao FROM coautores WHERE submissao_id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id_submissao);
        $stmt->execute();
        $result = $stmt->get_result();

        $coautores = [];
        while ($co = $result->fetch_assoc()) {
            $coautores[] = $co;
        }
        return $coautores;
    }

    /* Função de Busca dos Títulos e Anos do Evento para os filtros */
    public function buscarDadosFiltros(): array
    {
        $dados = [
            'eventos' => [],
            'anos' => []
        ];

        $sqlEventos = "SELECT id_evento, titulo FROM evento ORDER BY data_evento DESC";
        $resultEventos = $this->con->query($sqlEventos);
        if ($resultEventos) {
            $dados['eventos'] = $resultEventos->fetch_all(MYSQLI_ASSOC);
        }

        $sqlAnos = "SELECT DISTINCT YEAR(data_evento) as ano FROM evento ORDER BY ano DESC";
        $resultAnos = $this->con->query($sqlAnos);
        if ($resultAnos) {
            $dados['anos'] = $resultAnos->fetch_all(MYSQLI_ASSOC);
        }

        return $dados;
    }

    /* Função de Buscar de todos os arquivos submetidos */
    public function contarTotalArquivos(?string $termo, ?int $eventoId, ?int $ano): int
    {

        /* Sql buscando todos os arquivos submetidos com o evento, usuario (autor) associado */
        $sql = "SELECT COUNT(DISTINCT s.id_submissao) as total FROM submissao s
                INNER JOIN evento e ON e.id_evento = s.evento_id
                INNER JOIN funcao_usuario fu ON s.funcao_usuario_id = fu.id_funcao_usuario
                INNER JOIN usuario u ON fu.usuario_id = u.id_usuario
                LEFT JOIN coautores as ca ON ca.submissao_id = s.id_submissao
                WHERE 1=1";

        $params = [];
        $tipos = "";

        if (!empty($termo)) {
            $sql .= " AND (s.titulo LIKE ? OR s.palavras_chave LIKE ? OR u.nome_usuario LIKE ? OR ca.nome_coautor LIKE ?)";
            $busca = "%$termo%";
            array_push($params, $busca, $busca, $busca, $busca);
            $tipos .= "ssss";
        }

        if (!empty($eventoId)) {
            $sql .= " AND s.evento_id = ?";
            $params[] = $eventoId;
            $tipos .= "i";
        }

        if (!empty($ano)) {
            $sql .= " AND YEAR(e.data_evento) = ?";
            $params[] = $ano;
            $tipos .= "i";
        }

        $stmt = $this->con->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($tipos, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return (int) ($row['total'] ?? 0);

    }

    public function pesquisarArquivos(?string $termo, ?int $eventoId, ?int $ano, int $limite, int $offset): array
    {
        $arquivos = [];

        /* 
            O SQL realiza a busca integrando as tabelas de Submissão, Evento, Função, Usuário e Coautores. Utilizamos o INNER JOIN para conectar submissões, eventos e usuários, 
            pois uma submissão sempre deve estar vinculada a um evento e a um autor. 
            Já para os Coautores, utilizamos o LEFT JOIN. Isso ocorre porque nem toda submissão possui coautores. Com o LEFT JOIN, o SQL garante que a submissão apareça no 
            resultado final mesmo que a lista de coautores esteja vazia (retornando NULL nesses campos). Assim o SQL sempre retorna alguma coisa.
        */
        $sql = "SELECT DISTINCT s.*, e.titulo AS evento_titulo, e.data_evento, u.nome_usuario AS autor_nome FROM submissao s
                INNER JOIN evento e ON e.id_evento = s.evento_id
                INNER JOIN funcao_usuario fu ON s.funcao_usuario_id = fu.id_funcao_usuario
                INNER JOIN usuario u ON fu.usuario_id = u.id_usuario
                LEFT JOIN coautores as ca ON ca.submissao_id = s.id_submissao
                WHERE 1=1";

        $params = []; // Variável para armazenar os parametros da busca
        $tipos = ""; // Variável para armazenar os tipos de pesquisas, seja string, int ou outros.

        /* Caso a barra de pesquisa seja preenchida*/
        if (!empty($termo)) {
            /* Acrescento o sql anterior com a busca da informação preenchida (uma informação que verificar se existe em todas as colunas e tabelas.*/
            $sql .= " AND (
                        s.titulo LIKE ?
                        OR s.palavras_chave LIKE ?
                        OR u.nome_usuario LIKE ?
                        OR ca.nome_coautor LIKE ?
            )";
            $busca = "%$termo%"; // o % verifica se tem algo antes ou depois do valor digitado porque foi colocado antes e dps do termo da pesquisa
            array_push($params, $busca, $busca, $busca, $busca);
            $tipos .= "ssss";
        }

        /* Caso o filtro de seleção por evento seja marcado */
        if (!empty($eventoId)) {
            $sql .= " AND s.evento_id = ?";
            $params[] = $eventoId;
            $tipos .= "i";
        }

        /*Caso o filtro de seleção por ano seja marcado*/
        if (!empty($ano)) {
            /* a função year separa apenas o ano do campo data */
            $sql .= " AND YEAR(e.data_evento) = ?";
            $params[] = $ano;
            $tipos .= "i";
        }

        /* Separando a busca por datas recentes primeiro */
        $sql .= " ORDER BY e.data_evento DESC, s.data_envio DESC, s.hora_envio DESC";

        /* PAGINAÇÃO: Define qual pedaço dos dados o banco vai entregar */
        $sql .= " LIMIT ? OFFSET ?";

        $params[] = $limite;
        $params[] = $offset;
        $tipos .= "ii";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // Para cada arquivo, buscamos os coautores associados
                $row['coautores'] = $this->buscarCoautores((int) $row['id_submissao']);
                $arquivos[] = $row;
            }
        }

        return $arquivos;
    }

}

?>