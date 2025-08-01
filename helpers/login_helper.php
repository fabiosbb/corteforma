<?php

session_start();
date_default_timezone_set('Europe/Lisbon');

require '../requisitos.php'; 

function fazerLogin($login, $senha) {
   
    $usuario = selectSQLUnico("SELECT * FROM backoffice WHERE login = ?", [$login]);


    if ($usuario && password_verify($senha, $usuario["senha"])) {
        $_SESSION["usuario"] = $usuario;

        $data = date("H:i:s - d/m/Y");
        $id = $usuario["id"];
        iduSQL("UPDATE backoffice SET ultimo_acesso = ? WHERE id = ?", [$data, $id]);

        return true;
    }

    return false;
}

function logado() {
    return isset($_SESSION["usuario"]);
}

?>
