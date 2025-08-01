<?php
require_once("requisitos.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

function getTodosCarrossel() {
    return selectSQL("SELECT * FROM carrossel WHERE ordem = 1 ORDER BY id DESC");
}

function getCarrosselID($id) {
    return selectSQLUnico("SELECT * FROM carrossel WHERE id = :id", ["id" => $id]);
}

function getInfoHome() {
    return selectSQLUnico("SELECT * FROM home LIMIT 1");
}

function getServicosHome() {
    return selectSQL("SELECT * FROM servicos WHERE exibir_na_home = 1");
}

function getPortfolioHome() {
    return selectSQL("SELECT * FROM portfolio WHERE exibir_na_home = 1");
}

function getContatoPrincipal() {
    return selectSQLUnico("SELECT * FROM contactos LIMIT 1");
}

?>
