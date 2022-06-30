<?php get_header();
global $post; ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <!-- article -->
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php the_content(); ?>


        </article>
        <!-- /article -->

    <?php endwhile; ?>

<?php else : ?>



<?php endif; ?>

<?php get_footer(); ?>