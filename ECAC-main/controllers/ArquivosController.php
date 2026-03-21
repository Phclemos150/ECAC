<?php
// Verificando se já está em um sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/ArquivosModel.php';

class ArquivosController
{
    private ArquivosModel $arquivosModel;

    public function __construct($con)
    {
        $this->arquivosModel = new ArquivosModel($con);
    }

    /* Função controla a lista de arquivos com páginação */
    public function listar(): array
    {
        try {
            $itensPorPagina = 10; // Quantidade de arquivos por página
            $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if ($paginaAtual < 1) $paginaAtual = 1;

            $offset = ($paginaAtual - 1) * $itensPorPagina; 

            /* Armazena os dados do filtro */ 
            $termo = $_GET['termo'] ?? null;
            $eventoId = !empty($_GET['evento']) ? (int) $_GET['evento'] : null;
            $ano = !empty($_GET['ano']) ? (int) $_GET['ano'] : null;

            /* Consulta o Total de Arquivos */
            $totalRegistros = $this->arquivosModel->contarTotalArquivos($termo, $eventoId, $ano);

            /* Busca apenas 10 por vez */ 
            $lista = $this->arquivosModel->pesquisarArquivos($termo, $eventoId, $ano, $itensPorPagina, $offset);

            /* Calcula o total de Páginas */ 
            $totalPaginas = ceil($totalRegistros / $itensPorPagina);

            if (empty($lista) && ($termo || $eventoId || $ano)) {
                $_SESSION['info_mensagem'] = "Nenhum arquivo encontrado para esta pesquisa!";
            }

            return [
                'lista' => $lista,
                'totalPaginas' => $totalPaginas,
                'paginaAtual' => $paginaAtual,
                'totalRegistros' => $totalRegistros
            ];

        } catch (Exception $e) {
            $_SESSION['info_mensagem'] = "Erro ao carregar arquivos. Tente novamente mais tarde!";
            return [
                'lista' => [],
                'totalPaginas' => 0,
                'paginaAtual' => 1,
                'totalRegistros' => 0
            ];
        }
    }

    /* Função que busca o Título e Ano do Evento para os Filtros */
    public function carregarFiltros(): array
    {
        return $this->arquivosModel->buscarDadosFiltros();
    }

}

$controller = new ArquivosController($con);

$dadosPaginados = $controller->listar();

$listaArquivos = $dadosPaginados['lista'];
$totalPaginas  = $dadosPaginados['totalPaginas'];
$paginaAtual   = $dadosPaginados['paginaAtual'];
$totalGeral    = $dadosPaginados['totalRegistros'];

$termo    = $_GET['termo'] ?? '';
$eventoId = $_GET['evento'] ?? '';
$ano      = $_GET['ano'] ?? '';

$filtros = $controller->carregarFiltros();
?>