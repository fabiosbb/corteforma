<?php
require_once("../helpers/recuperar_login_helper.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    $utilizador = buscarUtilizadorPorEmail($email);

    if ($utilizador) {
        $token = gerarToken();
        salvarTokenDeRecuperacao($utilizador["id"], $token);

        $link = "https://seudominio.com/redefinir.php?token=$token";
        mail($email, "Redefinir palavra-passe", "Clique no link para redefinir: $link");

        $mensagem = "Um link de recuperação foi enviado para o seu email.";
    } else {
        $erro = "Email não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Palavra-Passe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="estilo_backoffice.css">
</head>
<body class="bg-light d-flex align-items-center">

<div class="container pt-5">
  <div class="row justify-content-center pt-5">
    <div class="col-md-6 col-lg-5">

      <div class="card shadow p-4 rounded-4">

        <h3 class="text-center mb-4">Recuperar Palavra-Passe</h3>

        <?php if (isset($mensagem)): ?>
          <div class="alert alert-success text-center"><?= $mensagem ?></div>
        <?php endif; ?>

        <?php if (isset($erro)): ?>
          <div class="alert alert-danger text-center"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label for="email" class="form-label fonte_botoes pt-5 pb-2">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" required>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-outline-dark m-3 px-5 py-2 fonte_botoes">Enviar link de recuperação</button>
          </div>
        </form>

        <div class="text-center mt-3">
          <a href="index.php" class="text-dark ">Voltar ao login</a>
        </div>

      </div>

    </div>
  </div>
</div>

</body>
</html>
