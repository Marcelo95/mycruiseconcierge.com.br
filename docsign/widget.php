<div id="widget-docsign">
  <script src='<?php echo get_template_directory_uri() . "/docsign/js/embedded.js?v2"; ?>'></script>
 
  <div id='container-widget-docsign' style='height: 600px;'></div>

  <script type='text/javascript'>
    var widget;

    function runWidgetDocSign(user_key,callback) {

      if (widget) {
        widget.unmount();
      }

      widget = new Clicksign(user_key);

      widget.endpoint = "<?php echo isset($options["url_endpoint"]) ? $options["url_endpoint"] : ''; ?>";
      widget.origin = "<?php echo isset($options["url_origin"]) ? $options["url_origin"] : ''; ?>";

      console.log("origin", widget.origin);
      console.log("endpoint", widget.endpoint);
      console.log("user_key", user_key);

      widget.mount('container-widget-docsign');

      widget.on('loaded', function(ev) {
        console.log(ev);
        console.log('loaded!');

      });
      // widget.on('signed', async function(ev) {
      //   console.log(ev);
      //   console.log('signed!');

      // });

      callback(widget);
    };
  </script>
</div>