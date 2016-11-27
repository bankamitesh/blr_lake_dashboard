<?php 

    // atree_lake lists 
    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql as MySQL;

	use \com\indigloo\Logger ;
	use \com\indigloo\util\StringUtil as StringUtil ;

	use \com\yuktix\auth\Login as Login ;
    use \com\indigloo\exception\UIException as UIException;

    // @todo Login check 
    function change_row_to_object($row) {

        $lake = new \stdClass ;
        $lake->name = $row["name"];
        $lake->about = $row["about"];
        $lake->lat = $row["lat"] ;
        $lake->lon = $row["lon"] ;

        $lake->address = $row["address"] ;

        $lake->maxArea = $row["max_area"] ;
        $lake->maxVolume = $row["max_volume"] ;
        $lake->rechargeRate = $row["recharge_rate"] ;

        $lake->typeCode = $row["type_code"];
        $lake->agencyCode = $row["agency_code"];
        $lake->usageCode = $row["usage_code"];
        $lake->id = $row["id"];
        
        return $lake ;

    }
	
	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    // PHP $_REQUEST only works for x-www-form-urlencoded content type
    // so we have to get the raw data when content-type is application/json 
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;

    $mysqli = MySQL\Connection::getInstance()->getHandle();
    $sql = " select * from atree_lake " ;
    $rows = MySQL\Helper::fetchRows($mysqli, $sql);
    $result = array() ;

    foreach ($rows as $row) {
        $lake = change_row_to_object($row) ;
        array_push($result, $lake);
    }

    // API response 
    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->result = $result ;

    echo json_encode($responseObj) ;
    exit(0) ;


?>