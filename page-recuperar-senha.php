<?php get_header(); ?>

<?php
global $wpdb;

$error = '';
$success = '';

// check if we're in reset form
if (isset($_POST['action']) && 'reset' == $_POST['action']) {
	$email = trim($_POST['user_login']);
	$mailOk = false;

	if (empty($email)) {
		$error = 'Digite um nome de usuário ou endereço de e-mail..';
	} else if (!is_email($email)) {
		$error = 'Nome de usuário ou endereço de e-mail inválido.';
	} else if (!email_exists($email)) {
		$error = 'Não há nenhum usuário registrado com esse endereço de e-mail.';
	} else {

		$mailOk = retrieve_password2();

		if ($mailOk) {

			$success = 'Verifique seu endereço de e-mail para sua nova senha.';
		} else {
			$error = 'Oops Ocorreu um erro ao atualizar sua conta.';
		}
	};





	if (!empty($error))
		echo '<div class="alert alert-danger"><p class="error">' . $error . '</p></div>';

	if (!empty($success))
		echo '<div class="alert alert-success"><p class="success">' . $success . '</p></div>';
}
?>

<section>

	<h1 class="title1"> ESQUECI MINHA SENHA </h1>
	<p class="subtitle1">Digite seu e mail para recuperação de senha.</p>

	<div class="area-form" style="margin-top:70px;">
		<form method="post" class="row margin-top-30" style="min-height: 125px;">
			<fieldset class="text-left">
				<p class="col-sm-12">
					<?php $user_login = isset($_POST['user_login']) ? $_POST['user_login'] : ''; ?>
					<input type="text" required placeholder="e-mail cadastrado" class="" name="user_login" id="user_login" value="<?php echo $user_login; ?>" />
				</p>
				<p class="col-sm-12 text-right">
					<input type="hidden" name="action" value="reset" />
					<input type="submit" value="Enviar" class="botao1" id="submit" />
				</p>

				<div class="clearfix"></div>
				<br><br>

				<p class="col-sm-12 text-center">Você receberá no e-mail cadastrado um link <br> com sua nova senha.</p>
				<br><br>
				<p class="col-sm-12 text-center">Área exclusiva do Agente de Viagem <br> Se você ainda não tem cadastrado <a href="<?php echo home_url('registrar'); ?>">clique aqui</a></p>
			</fieldset>
		</form>
	</div>


</section>


<?php get_footer(); ?>