<?php

require_once("../requisitos.php");

$form = isset($_POST["login"]) && isset($_POST["senha"]);

if ($form) {
    $login = $_POST["login"];
    $senha = $_POST["senha"];

    if (fazerlogin($login, $senha)) {
        header("location:home.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center">

    <div class="container pt-5">
        <div class="row justify-content-center pt-5">
            <div class="col-md-6 col-lg-5">

                <div class="card shadow rounded-4 p-4">

                    <h2 class="text-center mb-4">Backoffice</h2>

                    <?php if ($form): ?>
                        <div class="alert alert-danger text-center" role="alert">
                            Login inválido. Verifique os seus dados.
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">

                        <div class="mb-3">
                            <label for="login" class="form-label">Login:</label>
                            <input type="text" class="form-control" name="login" id="login" placeholder="Digite seu login" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <input type="password" class="form-control" name="senha" id="senha" placeholder="Digite sua senha" required>
                        </div>

                        <div class="gap-2 text-center">
                            <button type="submit" class="btn btn-outline-dark m-3 px-5 py-2">Entrar</button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="recuperar.php" class="text-dark">Esqueceu a palavra-passe?</a>

                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

</body>
</html>
