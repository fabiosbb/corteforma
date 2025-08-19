<?php

require_once 'helpers/requisitos.php';

$portfolio = getPortfolio();

$id_portfolio = isset($_GET['id']) ? (int)$_GET['id'] : ($portfolio[0]['id'] ?? null);
$paginaAtual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$quantidadePorPagina = 4;

$imagensCompleta = getPortfolioServico($id_portfolio);

$paginacao = paginacaoLista($imagensCompleta, $paginaAtual, $quantidadePorPagina);

$imagens = $paginacao['itens'];
$total_paginas = $paginacao['paginas'];

?>

<main class="container-fluid cor_fundo pt-5">

    <div class="row d-flex justify-content-evenly pt-5 pb-5 text-center">

        <?php foreach ($portfolio as $p): ?>

            <div class="col-4 card_servicos d-flex justify-content-center align-items-center fonte_oswald1 <?= ($p['id'] == $id_portfolio) ? 'active-card' : '' ?>">
                <a href="portfolio.php?id=<?= $p['id'] ?>"><?= mb_strtoupper($p['titulo'] ?? 'PORTFÓLIO') ?></a>
            </div>

        <?php endforeach; ?>

    </div>

 <div class="row justify-content-center galeria-imagens ">
  <?php foreach ($imagens as $img): ?>
    <div class="col-lg-6 col-12 text-center pt-md-2 pb-md-2">
      <div class="img-container mt-5 mb-md-3 mb-lg-4 tamanho_imagem">
        <img src="<?= $img['imagem'] ?? 'imagens' ?>" alt="Imagem do portfolio" class="shadow-sm img-fluid ">
      </div>
      <p class="tamanho_legenda mb-2 mb-md-3 mb-lg-4"><?= $img['legenda'] ?? 'Legenda' ?></p>
    </div>
  <?php endforeach; ?>
</div>



    <div class="row pt-5 pb-5">

        <div class="col-12 d-flex justify-content-center">

            <nav aria-label="Page navigation">

                <ul class="pagination">

                    <li class="page-item <?php if($paginaAtual <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?id=<?= $id_portfolio ?>&page=<?= max(1, $paginaAtual - 1); ?>"><</a>
                    </li>

                    <?php for($i = 1; $i <= $total_paginas; $i++): ?>

                        <li class="page-item <?php if($paginaAtual == $i) echo 'active'; ?>">
                            <a class="page-link" href="?id=<?= $id_portfolio ?>&page=<?= $i; ?>"><?= $i; ?></a>
                        </li>

                    <?php endfor; ?>

                    <li class="page-item <?php if($paginaAtual >= $total_paginas) echo 'disabled'; ?>">
                        <a class="page-link" href="?id=<?= $id_portfolio ?>&page=<?= min($total_paginas, $paginaAtual + 1); ?>">></a>
                    </li>

                </ul>

            </nav>

        </div>

    </div>

</main>
