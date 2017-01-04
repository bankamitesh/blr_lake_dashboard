<?php


    $x = $_GET["x"] ;
    sleep(mt_rand(0,3));

    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->response = "operation success!" ;
    $responseObj->result = $x * 2  ;
    echo json_encode($responseObj) ;
    exit(0) ;

?>
