<?php

class EventoModel
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    /* Função de busca dos eventos */
    public function listarEventos(): array
    {
        $sqlBuscaEventos = "SELECT * FROM evento ORDER BY data_evento ASC"; // Busca os eventos em ordem crescente
        $resultEventos = $this->con->query($sqlBuscaEventos);

        $eventos = [];

        if ($resultEventos && $resultEventos->num_rows > 0) {
            while ($row = $resultEventos->fetch_assoc()) {
                $id_evento = $row["id_evento"];
                $row["atividades"] = $this->buscarAtividadesEvento($id_evento); // Chama a função de busca da programação das atividades do evento
                $eventos[] = $row;
            }
        }
        return $eventos;
    }

    /* Função de busca das atividades do Evento */
    public function buscarAtividadesEvento($id_evento): array
    {
        $sqlBuscaAtividades = "SELECT * FROM atividade_evento WHERE evento_id = ? ORDER BY horario_inicio ASC";

        $stmt = $this->con->prepare($sqlBuscaAtividades);
        $stmt->bind_param("i", $id_evento);
        $stmt->execute();
        $resultAtividades = $stmt->get_result();

        $atividades = [];

        if ($resultAtividades && $resultAtividades->num_rows > 0) {
            while ($row = $resultAtividades->fetch_assoc()) {
                $id_atividade = $row["id_atividade_evento"];
                $row["palestrantes"] = $this->buscarPalestrantes($id_atividade); // Chama a função de busca dos palestrantes da atividade
                $row["expositores"] = $this->buscarExpositores($id_atividade); // Chama a função de busca dos expositores da atividade
                $atividades[] = $row;
            }
        }

        $stmt->close();
        return $atividades;
    }

    /* Função de busca dos palestrantes da atividade */
    public function buscarPalestrantes($id_atividade): array
    {
        $sqlPalestrantes = "SELECT nome_palestrante, grau_academico, nome_curso, cargo, linkedin_url, instagram, mini_bio, foto_palestrante FROM palestrante WHERE atividade_evento_id = ?";

        $stmt = $this->con->prepare($sqlPalestrantes);
        $stmt->bind_param("i", $id_atividade);
        $stmt->execute();
        $resultPalestrantes = $stmt->get_result();

        $palestrantes = [];

        if ($resultPalestrantes && $resultPalestrantes->num_rows > 0) {
            while ($row = $resultPalestrantes->fetch_assoc()) {
                $palestrantes[] = $row;
            }
        }

        $stmt->close();
        return $palestrantes;
    }

    /* Função de busca dos expositores da atividade */
    public function buscarExpositores($id_atividade): array
    {
        $sqlExpositores = "SELECT nome_expositor, cargo, empresa, logo, link_empresa, descricao, foto_expositor FROM expositor WHERE atividade_evento_id = ?";

        $stmt = $this->con->prepare($sqlExpositores);
        $stmt->bind_param("i", $id_atividade);
        $stmt->execute();
        $resultExpositores = $stmt->get_result();

        $expositores = [];

        if($resultExpositores && $resultExpositores->num_rows >0 ){
            while ($row = $resultExpositores->fetch_assoc()){
                $expositores[] = $row;
            }
        }

        $stmt->close();
        return $expositores;
    }
}

?>