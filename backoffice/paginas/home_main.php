<?php

require_once("../helpers/requisitos.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['usuario'];
?>

<main class="container-fluid pt-5">

    <div class="row text-center my-4">
        <h1 class="col-12"> Backoffice - Corte&forma</h1>
        <img src="logo.png" alt="">
    </div>

    <div class="text-center pt-5 pb-5">
        <p class="mt-3 fonte">Bem-vindo, <span class="fw-bold"><?= htmlspecialchars($usuario["nome"]) ?></span></p>
        <p class="fonte">Último acesso: <?= htmlspecialchars($usuario["ultimo_acesso"]) ?></p>
    </div>

    

    <div class="row g-4 justify-content-center mb-4 pt-5 ">
        <div class="col-md-3 text-center">
            <a href="../backoffice/gerir_home.php" class="btn btn-preto-invert w-100 py-3 fonte_botoes">🏠 Home</a>
        </div>

        <div class="col-md-3 text-center">
            <a href="../backoffice/gerir_sobre.php" class="btn btn-preto-invert w-100 py-3 fonte_botoes">🏢 Sobre</a>
        </div>

        <div class="col-md-3 text-center">
            <a href="../backoffice/gerir_portfolio.php" class="btn btn-preto-invert w-100 py-3 fonte_botoes">🖼️ Portfolio</a>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-3 text-center">
            <a href="../backoffice/gerir_servicos.php" class="btn btn-preto-invert w-100 py-3 fonte_botoes">🛠️ Serviços</a>
        </div>

        <div class="col-md-3 text-center">
            <a href="../backoffice/gerir_orcamentos.php" class="btn btn-preto-invert w-100 py-3 fonte_botoes">📬 Orçamentos</a>
        </div>

        <div class="col-md-3 text-center">
            <a href="../backoffice/gerir_contactos.php" class="btn btn-preto-invert w-100 py-3 fonte_botoes">📞 Contactos</a>
        </div>
    </div>

</main>
