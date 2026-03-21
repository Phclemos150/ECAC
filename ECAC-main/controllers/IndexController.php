<?php
// Verificando se já está em um sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/IndexModel.php';

class IndexController
{
    private IndexModel $indexModel;

    public function __construct($con)
    {
        $this->indexModel = new IndexModel($con);
    }

    /* Função para carregar todos os dados da página inicial com tratamento isolado */
    public function carregarDadosHome(): array
    {
        $dados = [
            'organizadores' => [],
            'cientificos'   => [],
            'patrocinadores' => []
        ];

        // 1. Carregar Comissão Organizadora
        try {
            $dados['organizadores'] = $this->indexModel->buscarComissaoOrganizadora();
        } catch (Exception $e) {
            $_SESSION['modal_erro_titulo'] = "Erro na Comissão Organizadora";
            $_SESSION['modal_erro_mensagem'] = "Não foi possível carregar os organizadores. Atualize a Página!";
        }

        // 2. Carregar Comissão Científica
        try {
            $dados['cientificos'] = $this->indexModel->buscarComissaoCientifica();
        } catch (Exception $e) {
            $_SESSION['modal_erro_titulo'] = "Erro na Comissão Científica";
            $_SESSION['modal_erro_mensagem'] = "Não foi possível carregar a equipe científica. Atualize a Página!";
        }

        // 3. Carregar Patrocinadores
        try {
            $dados['patrocinadores'] = $this->indexModel->buscarPatrocinadores();
        } catch (Exception $e) {
            $_SESSION['modal_erro_titulo'] = "Erro nos Patrocinadores";
            $_SESSION['modal_erro_mensagem'] = "Não foi possível carregar os parceiros do evento. Atualize a Página!";
        }

        return $dados;
    }
}