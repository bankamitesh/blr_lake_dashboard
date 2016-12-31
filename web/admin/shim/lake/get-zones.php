<?php 

    // atree_lake 
    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql as MySQL;

	use \com\indigloo\Logger ;
	use \com\indigloo\util\StringUtil as StringUtil ;
    use \com\indigloo\exception\UIException as UIException;

    use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\mysql\Lake as Lake ;

    // script:start 
	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;
    
    $zoneObj = Lake::getZones($postData->lakeId);
    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->result = $zoneObj ;

    echo json_encode($responseObj) ;
    exit(0) ;


?>