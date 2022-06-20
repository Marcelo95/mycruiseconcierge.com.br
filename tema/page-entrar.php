<?php get_header(); ?>


<section>
	
	<h1 class="title1"> BEM-VINDO </h1>
	<p class="text-center"> <span class="font-3">MyCruiseConcierge</span> </p>
	<p class="subtitle1">Faça seu login abaixo:</p>

<?php wp_login_form([
	'label_username' => '',
	'label_password' => '',
	'label_remember' => '',
	'label_log_in' => 'Entrar',
	'form_id' => 'form_login',
	'id_username' => 'form_username',
	'id_password' => 'form_password',
	'id_remember' => 'form_remember',
	'id_submit' => 'form_submit',
	'redirect' => '/',
]); 

?>

<a class="link-esqueci" href="<?php echo home_url('recuperar-senha'); ?>">esqueci minha senha</a>

<div class="clearfix"></div>
<br><br>
<p class="col-sm-12 text-center">Área exclusiva do Agente de Viagem <br> Se você ainda não tem cadastrado <a href="<?php echo home_url('registrar'); ?>">clique aqui</a></p>

</section>


<script type="text/javascript">
	
	var form = document.form_login;
		form.form_username.placeholder = "login (e-mail)";
		form.form_password.placeholder = "senha";
		form.form_remember.style.display = "none";

		form.form_submit.classList.add("botao1");


</script>

<style>
	.login-remember {
		display: none;
	}
</style>

<?php get_footer(); ?>