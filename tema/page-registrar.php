<?php get_header(); ?>

<section>
	
	<h1 class="title1"> QUERO ME CADASTRAR </h1>
	<p class="subtitle1">
	 Preencha o fomulário abaixo para solicitar seu acesso.
	</p>

	<?php echo do_shortcode('[contact-form-7 title="Quero me cadastrar"]'); ?>

<div class="clearfix"></div>
<br><br>

	<p class="text-center">
		Área exclusiva do Agente de Viagem.
	</p>

</section>


<script>
	jQuery("document").ready(function() {


			var SPMaskBehavior = function(val) {
					return val.replace(/\D/g, '').length === 9 ? '(00) 00000-0000' : '(00) 0000-00009';
				},
				spOptions = {
					onKeyPress: function(val, e, field, options) {
						field.mask(SPMaskBehavior.apply({}, arguments), options);
					}
				};

			jQuery('.tel').unmask().mask(SPMaskBehavior, spOptions);




	})
</script>


<?php get_footer(); ?>