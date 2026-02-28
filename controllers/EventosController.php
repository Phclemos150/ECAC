<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/EventosModel.php';

class EventoController
{
    private EventoModel $eventoModel;

    public function __construct($con)
    {
        $this->eventoModel = new EventoModel($con);
    }

    /* Função para carregar a lista de eventos com as atividades e palestrantes */
    public function carregarEventos(): array
    {
        try{
            $eventos = $this->eventoModel->listarEventos();

            return $eventos;

        } catch (Exception $e) {
            $_SESSION['modal_erro_titulo'] = "Erro ao Carregar Eventos";
            $_SESSION['modal_erro_mensagem'] = "Não foi possível carregar a programação. Tente novamente mais tarde!";
            return [];
        }
    }
}

?>