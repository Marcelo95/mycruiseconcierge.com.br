<?php /* Template Name: Home Template */ ?>



<?php get_header(); ?>
<style>
	.bg {
		background-size: cover !important;
		background-position: center !important;
	}
</style>



		<?php if (isset($_GET["cbPage"])) :  ?>
			<style>
				html .bg {
					background: #f4f4f4;
				}

				.home .box-white {
					width: 100%;
					max-width: 1163px;
				}
			</style>

<main role="main">
	<!-- section -->
	<section>
			<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

			<script src="https://cb-lasarenas-nusfer.istinfor.com/includes/jquery/jsparent.js" type="text/javascript"></script>

			<iframe id="iframeMiniCruiseSearcher" name="iframeMiniCruiseSearcher" class="iframeCruiseBrowser" frameborder="0" width="100%" height="400" onload="onloadiframe(this)" src="https://cb-lasarenas-nusfer.istinfor.com/inici.aspx?iframeid=iframeCruiseSearcher"></iframe>

                </section>
	<!-- /section -->
</main>


		<?php else :  ?>

			<?php get_template_part('content-home'); ?>

		<?php endif;  ?>






<?php get_footer(); ?>