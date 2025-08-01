<?php

require_once 'helpers/requisitos.php';

$sobre = getSobre();



?>
    <main class="container-fluid">

    <?php foreach ($sobre as $index => $s): ?>

        <div class="row <?= $index > 0 ? 'g-0 cor_fundo d-flex flex-md-row flex-column' : '' ?> <?= $index === 2 ? 'small-screen-reverse' : '' ?>">

            <?php if ($index === 0): ?>


                <div class="col-12 carousel-img">
                    <img src="<?= htmlspecialchars($s['imagem']) ?>" class="img-fluid w-100 d-block h-100" style="object-fit: cover;" alt="Imagem <?= $index + 1 ?>">
                </div>

                <div class="col-6 mx-auto pt-5 pb-5 w-md-100 w-75">
                    <p class="tamanho_legenda"><?= nl2br(htmlspecialchars($s['texto'])) ?></p>
                </div>

            <?php elseif ($index === 1): ?>


            <div class="col-md-6 col-12 order-md-2 order-1">
                <img src="<?= htmlspecialchars($s['imagem']) ?>" class="img-fluid w-100 h-100 d-block" style="object-fit: cover;" alt="Imagem <?= $index + 1 ?>">
            </div>

            <div class="col-md-5 col-12 order-md-2 order-1 d-flex flex-column justify-content-center mx-auto ps-5 pe-5 pt-5 pb-small">
                <p class="tamanho_legenda"><?= nl2br(htmlspecialchars($s['texto'])) ?></p>
            </div>

            <?php else: ?>

                <div class="col-md-5 col-12 order-md-2 order-1 d-flex flex-column justify-content-center mx-auto ps-5 pe-5 pt-5 pb-small">
                    <p class="tamanho_legenda"><?= nl2br(htmlspecialchars($s['texto'])) ?></p>
                </div>

                <div class="col-md-6 col-12 order-md-2 order-1">
                    <img src="<?= htmlspecialchars($s['imagem']) ?>" class="img-fluid w-100 h-100 d-block" style="object-fit: cover;" alt="Imagem <?= $index + 1 ?>">
                </div>

            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</main>
