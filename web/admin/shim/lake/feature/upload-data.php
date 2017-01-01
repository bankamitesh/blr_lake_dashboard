<?php 

    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql\PDOWrapper;
    
	use \com\indigloo\Logger ;
    use \com\indigloo\exception\UIException as UIException;

	use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\mysql\Sensor as Sensor ;
    use \com\yuktix\lake\mysql\Feature as Feature ;
   
	
	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    $postData = NULL ;
    $rawPostData = file_get_contents("php://input");

    if(Config::getInstance()->is_debug()) {
        Logger::getInstance()->debug($rawPostData);
    }

    // @debug 
    Logger::getInstance()->info($rawPostData);

    $postData = json_decode($rawPostData) ;
    
    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->response = "lake feature data upload is success!" ;
    //$responseObj->featureId = $featureId ;
    echo json_encode($responseObj) ;
    exit(0) ;


?>
