<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' -'; } ?> <?php bloginfo('name'); ?></title>

	<link href="//www.google-analytics.com" rel="dns-prefetch">
	<link href="<?php echo get_template_directory_uri(); ?>/img/icons/icon-logo2.png?v2" rel="shortcut icon">
	<link href="<?php echo get_template_directory_uri(); ?>/img/icons/icon-logo2.png?v2" rel="apple-touch-icon-precomposed">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<?php // if(is_home()){ ?>
	<meta name="viewport" content="width=1800, initial-scale=1.0">
	<?php // }?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">


	<meta name="description" content="<?php bloginfo('description'); ?>">

	<?php wp_head(); ?>


</head>
<body <?php body_class(); ?>>


	<!-- header -->
	<header class="header clear" role="banner">

		<!--Header builder BEGIN-->
		<?php do_action('stm_hb', array('header' => 'stm_hb_settings')); ?>
		<!--Header builder END-->
		
	</header>
	<!-- /header -->

	<div class="bg">
		<div class="box-white">

