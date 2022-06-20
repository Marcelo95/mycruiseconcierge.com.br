<?php if( $_GET["redirect_to"] || is_wplogin() ): ?>
   
<link rel="stylesheet" href="<?php echo admin_url() . '/css/login.min.css?v='. version() ; ?>">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/includes/templates/layout-login/buttons.css?v='. version() ; ?>">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/includes/templates/layout-login/style.css?v='. version() ; ?>">
<?php endif; ?>



<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/style.css?v4='. version() ; ?>">

      <style type="text/css">
        body {
            opacity: 0;
                font-family: 'Poly' !important;
        }
        h1 {
            color: transparent;
        }
        h1 a { 
            background-image: url('<?php echo get_template_directory_uri(); ?>/img/logo.png?v1') !important;
            background-size: contain !important;
            width: 100% !important;
            background-position: center !important;
        }
        div#login form {
            background: white;
            padding: 11px;
            width: 100%;
            padding: 33px;
        }

        div#login {
            width: 400px;
        }

        .login #nav {
            background: #fff;
            margin: 0px;
            padding: 20px;
        }

        html #backtoblog {
            background: #fff;
            margin: 0px;
            padding: 0 13px 36px!important;
        }

        .login #login_error, .login .message, .login .success {
            margin: 0px;
        }

        html .wp-core-ui .button-primary {
            background: #374555;
            border-color: #374555;
            box-shadow: none;;
            color: #fff;
            text-decoration: none;
            text-shadow: none;
            text-transform:uppercase;
        }

    </style>

    <script>
        setTimeout(function (argument) {
        // body...
        document.body.style.opacity = "1";
        document.body.classList.add("bg");

        document.querySelector(".login h1 a").href = "<?php echo home_url(); ?>";

    },800)
</script>