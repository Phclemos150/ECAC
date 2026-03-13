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

    public function listar(): array
    {
        try {
            $lista = $this->arquivosModel->listarArquivos();

            if (empty($lista)) {
                $_SESSION['info_mensagem'] = "Nenhum arquivo encontrado!";
            }

            return $lista;
        } catch (Exception $e) {
            $_SESSION['info_mensagem'] = "Erro ao carregar arquivos. Tente novamente mais tarde!";
            return [];
        }
    }

}

$controller = new ArquivosController($con);
$listaArquivos = $controller->listar();

?>