<?php

require_once("../requisitos.php");

$form = isset($_POST["login"]) && isset($_POST["senha"]);

if($form){

    $login = $_POST["login"];
    $senha = $_POST["senha"];

    if(fazerlogin($login, $senha)){
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
    <title>Backoffice</title>

    <link rel = "stylesheet" href = "estilo.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</head>
<body>

<div class="container-fluid">
    
    <div class="row">

        <div class="col-12 text-center mt-5">
            <h1>Backoffice</h1>
        </div>

    </div>

    <div class="row">

        <?php if($form): ?>

            <div class="col-12 text-danger h5 text-center">LOGIN INVALIDO</div>

        <?php endif; ?>    

        <div class="col-12 text-center">

            <form action="" method="POST" id="login">

                <br><br>
                <input type="text" name="login" placeholder="Login" autofocus required>
                <br><br>
                <input type="password" name="senha" placeholder="Senha" required>
                <br><br>
                <input type="submit" value="Entrar">
            </form>

        </div>


    </div>

</div>


    
</body>
</html>