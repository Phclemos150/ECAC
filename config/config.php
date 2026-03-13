<?php
// Verificando se já está em um sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Sao_Paulo'); // Configurando o fuso horário de Brasília 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $con = new mysqli("localhost", "root", "", "ecac");
    $con->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    die("Erro ao conectar com o banco de dados.");
}

?>