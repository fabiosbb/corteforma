<?php

require_once("requisitos.php");


function getServicos() {
    return selectSQL("SELECT * FROM servicos");
}

function getServicoPorId($id) {
    if (!is_numeric($id)) return null;
    return selectSQLUnico("SELECT * FROM servicos WHERE id = :id", ["id" => $id]);
}

function getImagensServico($servico_id) {
    return selectSQL("SELECT * FROM servicos_imagens WHERE servico_id = :id", ["id" => $servico_id]);
}


function paginacaoLista($listaCompleta, $paginaAtual = 1, $quantidadePorPagina = 4) {
    $quantidadeTotal = count($listaCompleta);
    $indiceInicial = ($paginaAtual - 1) * $quantidadePorPagina;
    $listaPaginada = array_slice($listaCompleta, $indiceInicial, $quantidadePorPagina);
    $totalDePaginas = ceil($quantidadeTotal / $quantidadePorPagina);

    return [
        'itens' => $listaPaginada,
        'paginas' => $totalDePaginas
    ];
}

?>
