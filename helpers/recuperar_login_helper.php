<?php

require_once(__DIR__ . "/requisitos.php");

// 1. Buscar utilizador pelo login

function buscarUtilizadorPorLogin($login) {
    $sql = "SELECT * FROM backoffice WHERE login = ?";
    return selectSQLUnico($sql, [$login]);
}


// 2. Gerar token único para recuperação

function gerarToken() {
    return bin2hex(random_bytes(32)); 
}

// 3. Salvar token de recuperação no banco

function salvarTokenDeRecuperacao($utilizadorId, $token) {
    
    iduSQL("DELETE FROM tokens_recuperacao WHERE utilizador_id = ?", [$utilizadorId]);

    $expiraEm = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $sql = "INSERT INTO tokens_recuperacao (utilizador_id, token, expira_em) VALUES (?, ?, ?)";
    return iduSQL($sql, [$utilizadorId, $token, $expiraEm]);
}


// 4. Buscar utilizador por token válido

function buscarUtilizadorPorToken($token) {
    $sql = "SELECT b.* FROM tokens_recuperacao tr
            JOIN backoffice b ON tr.utilizador_id = b.id
            WHERE tr.token = ? AND tr.expira_em > NOW()
            LIMIT 1";
    return selectSQLUnico($sql, [$token]);
}

// 5. Atualizar senha do utilizador

function atualizarSenha($utilizadorId, $novaSenha) {
    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $sql = "UPDATE backoffice SET senha = ? WHERE id = ?";
    return iduSQL($sql, [$hash, $utilizadorId]);
}

// 6. Apagar token após uso

function apagarToken($token) {
    $sql = "DELETE FROM tokens_recuperacao WHERE token = ?";
    return iduSQL($sql, [$token]);
}

function buscarUtilizadorPorEmail($email) {
    $sql = "SELECT * FROM backoffice WHERE email = ?";
    return selectSQLUnico($sql, [$email]);
}

?>
