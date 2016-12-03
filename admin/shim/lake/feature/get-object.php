<?php 

    // atree_lake lists 
    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql as MySQL;

	use \com\indigloo\Logger ;
	use \com\indigloo\util\StringUtil as StringUtil ;
    use \com\indigloo\exception\UIException as UIException;

    use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\data\DBRowConverter as DBRowConverter ;

    function send_error_400($param) {
        // API response 
        $xmsg = sprintf("required parameter %s is missing",$param) ;
        $responseObj = new \stdClass ;
        $responseObj->code = 400;
        $responseObj->error = $xmsg ;
        echo json_encode($responseObj) ;
        exit(0) ;
    }

	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    // PHP $_REQUEST only works for x-www-form-urlencoded content type
    // so we have to get the raw data when content-type is application/json 
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;

    if(!property_exists($postData, "lakeId")) {
        send_error_400("lakeId");
    }

    if(!property_exists($postData, "featureId")) {
        send_error_400("featureId");
    }

    $mysqli = MySQL\Connection::getInstance()->getHandle();
    $lakeId = $mysqli->real_escape_string($postData->lakeId);
    $featureId = $mysqli->real_escape_string($postData->featureId);

    $sql = " select * from atree_lake_feature where id = ".$featureId ;
    $row = MySQL\Helper::fetchRow($mysqli, $sql);
    $featureObj = DBRowConverter::convertFeatureRow($row) ;
    
    // API response 
    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->result = $featureObj ;

    echo json_encode($responseObj) ;
    exit(0) ;


?>