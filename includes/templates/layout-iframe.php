

<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/style.css?v='. version() ; ?>">

<style type="text/css">
    body {
        opacity: 0;
        background: #fff;
    }
    h1 { 
        display: none;
    }
    div#login form {
        background: white;
        width: 100%;
        box-shadow: none;
        margin: 0px;
        padding: 0;
    }

    div#login {
        width: 400px;
    }

    .login #nav, html #backtoblog, .login .message {
        display: none !important;
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
        //document.body.classList.add("bg");

        document.querySelector(".login h1 a").href = "<?php echo home_url(); ?>";

        var allElements = document.querySelectorAll("a, form");

        allElements.forEach(function (elem) {
         elem.target = "_top";
     })

    },800)
</script>