<?php

    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: filename=export.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    if (isset($_REQUEST['exportdata']) ) {
        print $_REQUEST['exportdata'];
    }
?>
