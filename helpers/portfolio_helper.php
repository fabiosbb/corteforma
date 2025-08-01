<?php

require_once 'base_dados_helper.php';

function getPortfolio() {
    return selectSQL("SELECT * FROM portfolio");
}

function getPortfolioPorId($id) {
    return selectSQLUnico("SELECT * FROM portfolio WHERE id = :id", ["id" => $id]);
}

function getPortfolioServico($servico_id) {
    return selectSQL("SELECT * FROM portfolio_imagens WHERE portfolio_id = :id", ["id" => $servico_id]);
}

function paginarArray($array, $pagina = 1, $itens_por_pagina = 4) {

    $total = count($array);
    $inicio = ($pagina - 1) * $itens_por_pagina;
    $dados = array_slice($array, $inicio, $itens_por_pagina);
    $total_paginas = ceil($total / $itens_por_pagina);

    return [
        'dados' => $dados,
        'total_paginas' => $total_paginas
    ];
}


function salvarImagemPortfolio($arquivo) {
    
    $pastaDestino = '../../imagens/';
    $nomeUnico = uniqid() . '_' . basename($arquivo['name']);
    $caminhoFinal = $pastaDestino . $nomeUnico;

    if (move_uploaded_file($arquivo['tmp_name'], $caminhoFinal)) {
        return 'imagens/' . $nomeUnico;
    }

    return null;
}

function criarItemPortfolio($caminhoImagem) {
    return iduSQL("INSERT INTO portfolio (imagem) VALUES (?)", [$caminhoImagem]);
}

function listarPortfolio() {
    return selectSQL("SELECT * FROM portfolio ORDER BY id DESC");
}

function excluirItemPortfolio($id) {
    $registro = selectSQLUnico("SELECT imagem FROM portfolio WHERE id = ?", [$id]);

    if ($registro) {
        $caminhoArquivo = '../../' . $registro['imagem'];
        if (file_exists($caminhoArquivo)) {
            unlink($caminhoArquivo);
        }

        return iduSQL("DELETE FROM portfolio WHERE id = ?", [$id]);
    }

    return false;
}

?>
