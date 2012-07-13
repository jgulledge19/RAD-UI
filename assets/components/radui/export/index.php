<?php

$filename = 'export';
if ( isset($_REQUEST['filename']) ) {
    $filename = $_REQUEST['filename'];
}

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=".$filename.".csv");
header("Pragma: no-cache");
header("Expires: 0");

print $_REQUEST['exportdata'];
