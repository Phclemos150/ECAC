<?php

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try{
        $con = new mysqli("localhost","root","","ecac");
        $con->set_charset("utf8mb4");
    }  catch(mysqli_sql_exception $e){
        error_log($e->getMessage());
        die("Erro ao conectar com o banco de dados.");
    }

?>