<?php

use App\DocSign\DocSign;

require_once(__DIR__ . '/docsign/DocSign.class.php');

$doc = new DocSign(get_the_permalink());

get_header(); ?>

<section>

    <h1 class="title2 text-center text-uppercase"> ASSINAR DOCUMENTO </h1>
    <p class="subtitle1">
        Assine o documento digital abaixo
    </p>

    <div class="content-widget">
        <?php $doc->widget(); ?>
    </div>

    <div class="congratulations hide">
        <h3 class="text-center text-uppercase" style="margin-top: 100px;">Obrigado, entraremos em contato o mais breve possível. <br> Pode fechar essa janela.</h3>
    </div>


</section>


<script>
    (function($) {
        $("document").ready(function() {

            runWidgetDocSign(window.document.location.hash.slice(1), function() {
                setTimeout(function() {


                    widget.on('signed', function(ev) {
                        console.log("signed 2 - ok ");

                        $(".content-widget").addClass("hide");
                        $(".congratulations").removeClass("hide");


                    });

                }, 700);
            });
        });


    })(jQuery);
</script>

<style>
    .bg {
        background: #f4f4f4;
    }

    html .box-white {
        max-width: 1163px;
        width: 100%;
        padding: 8px 30px;
        min-height: 450px;
    }
</style>


<?php get_footer(); ?>