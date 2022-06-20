
<?php
if (!isset( $theads )) {
    echo "No theads";
}
if (!isset( $all_contacts )) {
    echo "No all_contacts";
}

?>

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