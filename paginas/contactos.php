<?php

require_once 'helpers/requisitos.php';

$contactos = getContactos();

?>

<main>  

    <div class="container-fluid cor_fundo pt-5 pb-5">

        <div class="row ps-5 pe-5 pt-5">

            <h1 class="pb-5 tamanho_h fonte_oswald">Envie-nos uma Mensagem</h1>

            <div class="col-12 d-flex flex-column">

                <form action="paginas/receber_orcamento.php" method="post">

                    <label for="nome" class="visually-hidden">Nome</label>
                    <input type="text" class="form-label estilo_input w-100" name="nome" id="nome" placeholder="  Nome" required>

                    <label for="email" class="visually-hidden">Email</label>
                    <input type="email" name="email" id="email" placeholder="  Email" class="form-label estilo_input w-100" required>

                    <label for="mensagem" class="visually-hidden">Mensagem</label>
                    <textarea class="form-control mb-5" id="mensagem" rows="4" name="mensagem" placeholder="Mensagem" required></textarea>

                    <input class="botao_enviar" type="submit" id="submit" value="Enviar">

                </form>

            </div>

        </div>

    </div>


    <div class="row">

        <div class="col-12 ajuste_mapa">
            
            <iframe  class="w-100" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3004.5463714064313!2d-8.615178127861036!3d41.144427757005786!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd2464e3da1099a3%3A0x72227c8a582c780!2sR.%20das%20Flores%20198%2C%20Porto%2C%20Portugal!5e0!3m2!1spt-BR!2sbr!4v1748040545915!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            
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

    





