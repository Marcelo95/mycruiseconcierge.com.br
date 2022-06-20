<?php get_header(); ?>

		<?php 

		if (isset($_GET['id'])) {
			$page_id = $_GET['id'];  //Page ID
			$page_data = get_page($page_id); 
			$title = $page_data->post_title; 
			$content = apply_filters( 'the_content', $page_data->post_content);
			?>


			<h1 class="title2 text-center text-uppercase"> Contrato de Pré-Embarque  </h1>
			<p class="text-center"> <span class="font-3"> <?php echo $title; ?> </span> </p>

			<div class="termos-e-condicoes">
				<?php echo $content; ?>
			</div>


		<?php } else {?>


			<h1 class="title2 text-center text-uppercase"> Contrato não encontrato! </h1>

		<?php };?>


<style>
	html .box-white {
    max-width: 1200px;
}

</style>



<?php get_footer(); ?>