<?php

require_once 'helpers/requisitos.php';

$carrossel = getTodosCarrossel();
$home = getInfoHome();
$servicos = getServicosHome();
$servicos = array_slice($servicos, 0, 3); 
$portfolio = getPortfolioHome();
$portfolio = array_slice($portfolio, 0, 3); 
$contactos = getContatoPrincipal();


?>

<main class="container-fluid px-0 banner">

    <div id="carouselExampleControls" class="carousel slide mb-2" data-bs-ride="carousel">
    
        <div class="carousel-inner">

            <?php foreach ($carrossel as $index => $item): ?>

                <div class="carousel-item  <?= $index === 0 ? 'active' : '' ?>">

                    <img src="<?= htmlspecialchars($item['imagem']) ?>" class="d-block w-100 carousel-img" alt="Imagem">
                    
                </div>

            <?php endforeach; ?>

        </div>
    
        <button class="carousel-control-prev carousel_button" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">

            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>

        </button>

        <button class="carousel-control-next carousel_button" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">

            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próxima</span>
            
        </button>

    </div>

    <div class="row">

        <div class="col-10  text-center pb-3 mx-auto">

            <h1 class=" "><?= htmlspecialchars($home['titulo']) ?></h1>

            <p class="pt-5 pb-5 tamanho_legenda"><?= htmlspecialchars($home['texto']) ?></p>
        </div>

    </div>

    <div class="container-fluid cor_fundo">

        <div class="row">
            
            <div class="col-12 text-center pt-5 pb-4">
                <div class="detalhe_amarelo_linha"></div>
            </div>

            <div class="col-12 text-center pb-5 fonte_oswald">
                <h2 class="px-0 tamanho_h">Produtos a Medida</h2>
            </div>

        </div>

    </div>

    <div class="row cor_fundo  justify-content-center g-5">

        <?php foreach ($portfolio as $p): ?>

            <div class="col-12 col-md-3 col-sm-12 text-center ">

                <a href="portfolio.php?id=<?= $p['id'] ?>" id="text_decoration"">

                    <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>" class="img-fluid img-uniforme "/>


                    <p class="tamanho_legenda pt-2 pb-5"><?= htmlspecialchars($p['titulo']) ?></p>

                </a>

            </div>

        <?php endforeach; ?>

    </div>    

    <div class="container-fluid cor_fundo">

        <div class="row">

            <div class="col-12 text-center pt-5 pb-4">
                <div class="detalhe_amarelo_linha"></div>
            </div>

            <div class="col-12 text-center pb-5 fonte_oswald">
                <h2 class="px-0 tamanho_h">Serviços</h2>
            </div>

        </div>

    </div>

    <div class="row cor_fundo pb-5 justify-content-center g-5">

        <?php foreach ($servicos as $s): ?>

            <div class="col-12 col-md-3 col-sm-12 text-center pb-5">

                <a href="servicos.php?id=<?= $s['id'] ?>" id="text_decoration">

                   <img src="<?= htmlspecialchars($s['imagem']) ?>" alt="<?= htmlspecialchars($s['titulo']) ?>" class="img-fluid img-uniforme"/>

                    <p class="tamanho_legenda pt-2"><?= htmlspecialchars($s['titulo']) ?></p>

                </a>

            </div>

        <?php endforeach; ?>

    </div>    

    <div class="row cor_fundo justify-content-center pt-5 pb-5 contactos gap-5">

        <?php if (is_array($contactos) && !empty($contactos)): ?>

            <div class="col-12 col-md-3 text-center px-0 pt-5 pb-5 text-center">
                <img src="<?= htmlspecialchars($contactos['imagem']) ?>" alt="Imagem de Contactos" class="img_contactos img-fluid img_produtos galeria-imagens"/>
            </div>

            <div class="col-12 col-md-3 d-flex flex-column justify-content-center align-items-center px-0 ms-md-5 ps-md-5 posicao">

                <h2 class="pb-4 tamanho_h">Contactos</h2>

                <div class="d-flex flex-column gap-3 ps-5 tamanho_legenda">

                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-phone fa-2x"></i>
                        <span class="texto-contacto"><?= htmlspecialchars($contactos['telefone']) ?></span>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-envelope fa-2x"></i>
                        <span class="texto-contacto"><?= htmlspecialchars($contactos['email']) ?></span>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <i class="fab fa-linkedin fa-2x"></i>
                        <span class="texto-contacto"><?= htmlspecialchars($contactos['linkedin']) ?></span>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-map-marker-alt fa-2x"></i>
                        <span class="texto-contacto"><?= htmlspecialchars($contactos['morada']) ?></span>
                    </div>

                </div>

            </div>

        <?php else: ?>

            <p class="text-center">Informação de contactos não disponível no momento.</p>

        <?php endif; ?>

    </div>


</main>

    