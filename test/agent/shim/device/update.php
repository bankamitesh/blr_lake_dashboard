<?php
    include('lake-app.inc') ;
    include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\Logger ;
    use \com\yuktix\agent\dao\Device as DeviceDao ;

    set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    // PHP $_REQUEST only works for x-www-form-urlencoded content type
    // so we have to get the raw data when content-type is application/json 
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;

    DeviceDao::update($postData);

    // API response 
    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->result = "device update is success!" ;
    echo json_encode($responseObj) ;
    exit(0) ;

   
?>