<?php

require_once 'base_dados_helper.php';

function salvarImagemCarrossel($arquivo) {
  $pastaDestino = '../../imagens/';
  $nomeUnico = uniqid() . '_' . basename($arquivo['name']);
  $caminhoFinal = $pastaDestino . $nomeUnico;

  if (move_uploaded_file($arquivo['tmp_name'], $caminhoFinal)) {
    return 'imagens/' . $nomeUnico;
  }

  return null;
}

function criarImagemCarrossel($caminhoImagem) {
  return iduSQL("INSERT INTO carrossel (imagem) VALUES (?)", [$caminhoImagem]);
}

function listarCarrossel() {
  return selectSQL("SELECT * FROM carrossel ORDER BY id DESC");
}






































?>