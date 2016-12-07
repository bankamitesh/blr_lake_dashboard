<?php

    $file = "jakkur1.kmz" ;
    header('Content-Description: kmz download');
    header('Content-Type: application/vnd.google-earth.kmz');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;



?>
