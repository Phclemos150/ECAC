<?php

class IndexModel
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    /* Função de Consulta da Comissão Organizadora */
    public function buscarComissaoOrganizadora(): array
    {
        $sqlBuscaOrg = "SELECT u.nome_usuario, u.foto_perfil, co.funcao_org 
                        FROM comissao_org co
                        JOIN funcao_usuario fu ON co.funcao_usuario_id = fu.id_funcao_usuario
                        JOIN usuario u ON fu.usuario_id = u.id_usuario";
        
        $resultOrg = $this->con->query($sqlBuscaOrg);

        $organizadores = [];

        if ($resultOrg && $resultOrg->num_rows > 0) {
            while ($row = $resultOrg->fetch_assoc()) {
                $organizadores[] = $row;
            }
        }

        return $organizadores;
    }

    /* Função de Consulta da Comissão Ciêntifica */
    public function buscarComissaoCientifica(): array
    {
        $sqlBuscaCient = "SELECT u.nome_usuario, u.foto_perfil, cc.funcao_cient 
                          FROM comissao_cient cc
                          JOIN funcao_usuario fu ON cc.funcao_usuario_id = fu.id_funcao_usuario
                          JOIN usuario u ON fu.usuario_id = u.id_usuario";
        
        $resultCient = $this->con->query($sqlBuscaCient);

        $cientificos = [];

        if ($resultCient && $resultCient->num_rows > 0) {
            while ($row = $resultCient->fetch_assoc()) {
                $cientificos[] = $row;
            }
        }

        return $cientificos;
    }

    /* Função de Consulta de Patrocinadores */
    public function buscarPatrocinadores(): array
    {
        $sqlBuscaPatroc = "SELECT nome_empresa, logo, site_empresa, nivel_patrocinio 
                           FROM patrocinador 
                           ORDER BY FIELD(nivel_patrocinio, 'ouro', 'prata', 'bronze')";
        
        $resultPatroc = $this->con->query($sqlBuscaPatroc);

        $patrocinadores = [];

        if ($resultPatroc && $resultPatroc->num_rows > 0) {
            while ($row = $resultPatroc->fetch_assoc()) {
                $patrocinadores[] = $row;
            }
        }

        return $patrocinadores;
    }
}