<?php /* Template Name: Page Default */ ?>

<?php get_header(); global $post;?>

<?php 
get_template_part('pages/content', $post->post_name); 

?>


<?php get_footer(); ?>
