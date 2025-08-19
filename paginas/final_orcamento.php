<?php

require_once 'helpers/requisitos.php';

$contactos = getContactos();

?>

<main class="container-fluid text-center">

  <div class="container pt-5 pb-5">

    <div class="row justify-content-center pb-5">

      <div class="col-12 col-md-8">

        <h1 class="tamanho_h pt-5 pb-5">Obrigado pelo seu contacto!</h1>

        <p class="fonte_oswald_texto pb-2 pt-5">
          Recebemos a sua mensagem e entraremos em contacto o mais breve possível.
        </p>

        <p class="fonte_oswald_texto pb-2">
          Normalmente respondemos dentro de 48 horas.
        </p>

        <div class="pt-3 mt-5">
          <button class="botao_orcamento p-3 fonte_oswald_nav">Voltar ao Início</button>
        </div>

      </div>

    </div>

  </div>

  <div class="container-fluid">
    
    <div class="row">

      <div class="col-12 ajuste_mapa">

        <iframe class="w-100" height="450" style="border:0;" loading="lazy" allowfullscreen
          referrerpolicy="no-referrer-when-downgrade"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3004.5463714064313!2d-8.615178127861036!3d41.144427757005786!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd2464e3da1099a3%3A0x72227c8a582c780!2sR.%20das%20Flores%20198%2C%20Porto%2C%20Portugal!5e0!3m2!1spt-BR!2sbr!4v1748040545915!5m2!1spt-BR!2sbr">
        </iframe>

      </div>

    </div>

  </div>

  <div class="row cor_fundo justify-content-center pt-5 pb-5 contactos gap-5">

    <div class="col-12 col-md-3 text-center px-0">
      <img src="<?= $contactos['imagem'] ?>" alt="Imagem de Contactos" class="img_contactos img-fluid img_produtos w-100 h-100"/>
    </div>

    <div class="col-12 col-md-3 d-flex flex-column justify-content-center align-items-center px-0 ms-md-5 ps-md-5 posicao">

      <h3 class="pb-4 tamanho_h">Contactos</h3>

      <div class="d-flex flex-column gap-3 ps-5">

        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-phone fa-2x"></i>
            <span class="texto-contacto"><?= $contactos['telefone'] ?></span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-envelope fa-2x"></i>
            <span class="texto-contacto"><?= $contactos['email'] ?></span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <i class="fab fa-linkedin fa-2x"></i>
            <span class="texto-contacto"><?= $contactos['linkedin'] ?></span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-map-marker-alt fa-2x"></i>
            <span class="texto-contacto"><?= $contactos['morada'] ?></span>
        </div>

      </div>

    </div>

  </div>

</main>
