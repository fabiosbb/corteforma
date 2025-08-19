<?php

require_once("../helpers/recuperar_login_helper.php");

$token = $_GET["token"] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $novaSenha = $_POST["senha"];
    $token = $_POST["token"];

    $utilizador = buscarUtilizadorPorToken($token); 

    if ($utilizador) {
        atualizarSenha($utilizador["id"], $novaSenha); 

        echo "Senha redefinida com sucesso.";
        exit();
    } else {
        echo "Token inválido ou expirado.";
        exit();
    }
}
?>

<form method="POST">
    <h2>Nova Palavra-Passe</h2>
    <input type="password" name="senha" placeholder="Nova senha" required>
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <br><br>
    <input type="submit" value="Redefinir">
</form>
