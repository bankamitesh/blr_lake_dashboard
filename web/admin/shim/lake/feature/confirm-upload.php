<?php 

    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
	use \com\indigloo\Logger ;
    use \com\indigloo\exception\UIException as UIException;

	use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\dao\Feature as FeatureDao ;
    

	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    $postData = NULL ;
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;

    if(Config::getInstance()->is_debug()) {
        Logger::getInstance()->info(json_encode($postData->lakeId));
        Logger::getInstance()->info(json_encode($postData->featureObj));
        Logger::getInstance()->info(json_encode($postData->fileIds));

    }
    
    FeatureDao::uploadData($postData->lakeId, $postData->featureObj, $postData->fileIds);
    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->response = "lake feature data upload is success!" ;
    // $responseObj->featureId = $featureId ;
    echo json_encode($responseObj) ;
    exit(0) ;


?>