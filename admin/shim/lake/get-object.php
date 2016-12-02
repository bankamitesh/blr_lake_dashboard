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

	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    // PHP $_REQUEST only works for x-www-form-urlencoded content type
    // so we have to get the raw data when content-type is application/json 
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;

    if(!property_exists($postData, "lakeId")) {
        // API response 
        $xmsg = "required parameter lakeId is missing" ;
        $responseObj = new \stdClass ;
        $responseObj->code = 400;
        $responseObj->error = $xmsg ;
        echo json_encode($responseObj) ;
        exit(0) ;
    }

    $mysqli = MySQL\Connection::getInstance()->getHandle();
    $lakeId = $mysqli->real_escape_string($postData->lakeId);

    $sql = " select * from atree_lake where id = ".$lakeId ;
    $row = MySQL\Helper::fetchRow($mysqli, $sql);
    $lakeObj = DBRowConverter::convertLakeRow($row) ;
    
    // API response 
    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->result = $lakeObj ;

    echo json_encode($responseObj) ;
    exit(0) ;


?>