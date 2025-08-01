<?php

function getContactos() {
    return selectSQLUnico("SELECT * FROM contactos WHERE id = 1");
}

function getContactoPorId($id) {

    return selectSQLUnico("SELECT * FROM contactos WHERE id = :id", ["id" => $id]);
}


function inserirContacto($dados) {
    $sql = "INSERT INTO contactos (nome, email, linkedin, morada)  VALUES (:nome, :email, :linkedin, :morada)";
    return iduSQL($sql, $dados);
}

function atualizarContacto($dados) {

    $sql = "UPDATE contactos SET nome = :nome, email = :email, linkedin = :linkedin, morada = :morada WHERE id = :id";
    return iduSQL($sql, $dados);
}

function deletarContacto($id) {

    $sql = "DELETE FROM contactos WHERE id = :id";
    return iduSQL($sql, ["id" => $id]);
}

?>
