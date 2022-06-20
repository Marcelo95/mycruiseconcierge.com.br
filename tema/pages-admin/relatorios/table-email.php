<center>
    <h2>Formulário Pré-embarque</h2>
    <table id="relatorio1" class="widefat fixed" cellspacing="0">

    <tbody>

        <?php foreach ($all_contacts as $key => $_result) {
            $_values = array_values( json_decode(json_encode($_result), true) );
            ?>
                <?php foreach ($_values as $key1 => $_result1) { ?>

            <tr class="alternate">
                    <td class="column-columnname " style="text-align: right; font-weight: bold;">
                        <?php echo $theads[$key1]; ?>:
                    </td>
                    <td class="column-columnname ">
                        <?php echo $_result1; ?>
                    </td>
            </tr>

                <?php } ?>
        <?php } ?>


    </tbody>

</table>
</center>