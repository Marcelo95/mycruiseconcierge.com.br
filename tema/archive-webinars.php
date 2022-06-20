

<?php get_header(); ?>

<style>
    .bg {
        background: #f4f4f4;
    }

    html .box-white {
        background: transparent;
    }

    iframe,
    video {
        max-width: 100%;
        height: 250px;
    }

    html .box-white {
        max-width: 1200px;
    }

    .title-video {
        font-size: 19px;
        font-weight: 500 !important;
    }

    .title-video {
        font-size: 22px;
        min-height: 38px;
        display: flex;
        align-items: flex-end;
        justify-content: left;
        font: 500 16px 'Montserrat', sans-serif;
    }

    .box-white {
        border: none;
    }

    .linha-video {
        min-height: 350px;
    }

    .pagination {
        float: left;
        width: 100%;
        text-align: center;
    }
</style>

<main role="main">
    <!-- section -->
    <section>


        <h1 class="title2 text-center text-uppercase"> Webinars </h1>
        <p class="text-center">
            Confira os diferenciais e principais atualizações das companhias de cruzeiros.
        </p>
        <br><br>


        <?php


        get_template_part('loop-webinars'); ?>

        <?php get_template_part('pagination'); ?>

    </section>
    <!-- /section -->
</main>



<?php get_footer(); ?>