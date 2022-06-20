 <?php 

 global $wpdb;

 $date_init = date('Y-m-d', strtotime('-1 month'));
 $date_fim =  date("Y-m-d", strtotime('+1 day'));

 if(isset($_GET["date_init"])) $date_init = date("Y-m-d", strtotime( $_GET["date_init"] . " -1 day" ) );
 if(isset($_GET["date_fim"])) $date_fim =  date("Y-m-d", strtotime( $_GET["date_fim"] . " +1 day" ));

 $table_name = $wpdb->prefix.'users_contacts';
 $user_table_name = $wpdb->prefix.'users';


 $queryRelatorio = $wpdb->prepare( query(1, $table_name, $date_init, $date_fim)   , OBJECT );


 $all_contacts = $wpdb->get_results( $queryRelatorio );

 if ($all_contacts) {
     $theads = array_keys( json_decode(json_encode($all_contacts[0]), true) );
 }

 $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


 ?>

 <h1>Relatórios</h1>
 <h4>Pessoas que preencheram o fomulários Pré-Embarque</h4>
 <hr>

 <div class="wrap">

    <div class="tablenav top">

        <form action="<?php echo $actual_link; ?>"  method="GET">
            <input type="hidden" name="page" value="page_relatorios">
            <div class="alignleft actions bulkactions">
                <b for="bulk-action-selector-top" >Data de início</b>
                <br>
                <input type="date" placeholder="00/00/0000" value="<?php echo $date_init; ?>" name="date_init" required pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/i">
            </div>
            <div class="alignleft actions bulkactions">
                <b for="bulk-action-selector-top" >Data final</b> <br>
                <input type="date" placeholder="00/00/0000" value="<?php echo $date_fim; ?>" name="date_fim" required pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/i">
                <input type="submit" id="doaction" class="button action" value="Buscar">
            </div>

        </form>


    </div>
    <br>
    <br>

    <?php if( count( $all_contacts ) > 0 ): ?>

        <div class="table-reponsive">

           <table id="relatorio1" class="widefat fixed" cellspacing="0">
                <thead>
                    <tr>
                        <?php foreach ($theads as $key => $thead) { ?>
                            <th class="manage-column column-columnname" scope="col"><?php echo $thead; ?></th>

                        <?php } ?>


                    </tr>
                </thead>


                <tbody>

                    <?php foreach ($all_contacts as $key => $_result) {
                        $_values = array_values( json_decode(json_encode($_result), true) );
                        ?>

                        <tr class="alternate">
                            <?php foreach ($_values as $key1 => $_result1) { ?>
                                <td class="column-columnname ">
                                    <?php echo $_result1; ?>
                                </td>
                            <?php } ?>
                        </tr>

                    <?php } ?>


                </tbody>

                <tfoot>
                    <tr>

                        <?php foreach ($theads as $key => $thead) { ?>
                            <th class="manage-column column-columnname" scope="col"><?php echo $thead; ?></th>

                        <?php } ?>

                    </tr>
                </tfoot>
            </table> 

        </div>


        <br>
        <hr>
        <button class="action button" onclick='Utils.ExportarToExcell("#relatorio1", "relatorio-export")' >Exportar para o excel</button>


        <?php else: ?>
            <small>Nehum resultado encontrado, tente outra pesquisa</small>
        <?php endif; ?>
    </div>


    <script type="text/javascript">

        var Utils = {

            ExportarToExcell: (function() {
                var uri = 'data:application/vnd.ms-excel;base64,', 
                template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>', 
                base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }, 
                format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
                return function(table, name) {
                    if (!table.nodeType) table = document.querySelector(table);

                    var dt = new Date();
                    var day = dt.getDate();
                    var month = dt.getMonth() + 1;
                    var year = dt.getFullYear();
                    var hour = dt.getHours();
                    var mins = dt.getMinutes();
                    var sec = dt.getMilliseconds();
                    var postfix = year + "-" + month + "-" + day + "-" + hour + "-" + mins+"-"+sec;
                    var ctx = {worksheet: name || 'tabela-'+postfix, table: table.innerHTML}

                    var link1 = document.createElement("a");
                    link1.download = ctx.worksheet+".xls";
                    link1.href = uri + base64(format(template, ctx));
                    link1.click();

                    delete link1;

                }

            })()
        };


    </script>

    <style>

        table#relatorio1 {
            display: table-cell;
            width: 100%;
        }

        .table-reponsive {
            max-width: 100%;
            overflow: auto;
        }
    </style>