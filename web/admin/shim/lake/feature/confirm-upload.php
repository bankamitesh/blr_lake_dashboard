<?php 

    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
	use \com\indigloo\Logger ;
    use \com\indigloo\exception\UIException as UIException;

	use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\dao\File as FileDao ;
    

	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    $postData = NULL ;
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;

    if(Config::getInstance()->is_debug()) {
        Logger::getInstance()->info(json_encode($postData->fileIds));
    }
    
    $result = FileDao::getFeatureDataPreview($postData->fileIds);

    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->result = $result ;
    
    echo json_encode($responseObj) ;
    exit(0) ;


?>