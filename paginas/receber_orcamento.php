<?php
require_once("../helpers/requisitos.php");

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

if ($nome === '' || $email === '' || $mensagem === '') {

    header('Location: contacto.php?erro=1');
    exit;
}

iduSQL("INSERT INTO orcamentos (nome, email, mensagem, status) VALUES (:nome, :email, :mensagem, :status)", [
    'nome' => $nome,
    'email' => $email,
    'mensagem' => $mensagem,
    'status' => 'recebido'
]);


$assunto = "📩 Novo orçamento recebido";
$corpo = "Nome: $nome\nEmail: $email\nMensagem:\n$mensagem";
$destinatario = "fabiosbbranco@gmail.com"; 

$sucessoEmail = mail($destinatario, $assunto, $corpo);

if ($sucessoEmail) {
    
    header("Location: final_orcamento.php");
    exit;
} else {
    echo "❌ Falha ao enviar o email.";
}

header("Location: ../final_orcamento.php");
exit;
?>
