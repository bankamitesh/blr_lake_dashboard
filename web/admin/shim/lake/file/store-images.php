<?php 

    // lake images
    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
	use \com\indigloo\Logger ;
	use \com\indigloo\util\StringUtil as StringUtil ;
    use \com\indigloo\exception\UIException as UIException;

    use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\dao\Lake as LakeDao ;

    // script:start 
	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;
    
    LakeDao::storeImages($postData->lakeId, $postData->imageFileIds);

    // HTTP response 
    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->result = "image upload is success!" ;
    echo json_encode($responseObj) ;
    exit(0) ;


?>