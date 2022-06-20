<div class="row">


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <!-- article -->
        <article id="post-<?php the_ID(); ?>" <?php post_class('col-sm-4 linha-video'); ?>>

            <!-- post title -->
            <h2 class="title-video">
                <?php the_title(); ?>
            </h2>
            <!-- /post title -->



            <?php the_content();  ?>

            <?php //edit_post_link(); ?>

        </article>
        <!-- /article -->

    <?php endwhile; ?>

<?php else : ?>

    <!-- article -->
    <article class="col-sm-12">
        <h2><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h2>
    </article>
    <!-- /article -->

<?php endif; ?>


</div>