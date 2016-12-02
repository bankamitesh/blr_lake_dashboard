<?php 

    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql\PDOWrapper;
    use \com\indigloo\exception\DBException;

	use \com\indigloo\Logger ;
	use \com\indigloo\util\StringUtil as StringUtil ;

	use \com\yuktix\auth\Login as Login ;
    use \com\indigloo\exception\UIException as UIException;

	
	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    // PHP $_REQUEST only works for x-www-form-urlencoded content type
    // so we have to get the raw data when content-type is application/json 
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;
    
    $dbh = NULL ;

    try {

        $dbh = PDOWrapper::getHandle();
        $sql = "insert INTO atree_lake(name, cname,about,lat,lon,address, max_area, " 
                . " max_volume, recharge_rate, agency_code, type_code, usage_code, "
                . " created_on) VALUES (:name, :cname, :about, :lat, :lon, :address, "
                . ":max_area, :max_volume, :recharge_rate, :agency_code, :type_code, :usage_code, "
                . " now())" ; 

        // Tx start
        $dbh->beginTransaction();
        $stmt = $dbh->prepare($sql);
        
        // bind params 
        $cname = StringUtil::convertNameToKey($postData->name) ;
        $usageCode = json_encode($postData->usageCode);

        // @todo error check for required params
        $stmt->bindParam(":name",$postData->name, \PDO::PARAM_STR);
        $stmt->bindParam(":cname",$cname, \PDO::PARAM_STR);
        $stmt->bindParam(":about",$postData->about, \PDO::PARAM_STR);
        $stmt->bindParam(":lat",$postData->lat, \PDO::PARAM_STR);
        $stmt->bindParam(":lon",$postData->lon, \PDO::PARAM_STR);
        $stmt->bindParam(":address",$postData->address, \PDO::PARAM_STR);
        $stmt->bindParam(":max_area",$postData->maxArea, \PDO::PARAM_STR);
        $stmt->bindParam(":max_volume",$postData->maxVolume, \PDO::PARAM_STR);
        $stmt->bindParam(":recharge_rate",$postData->rechargeRate, \PDO::PARAM_STR);
        $stmt->bindParam(":agency_code",$postData->agencyCode, \PDO::PARAM_INT);
        $stmt->bindParam(":type_code",$postData->typeCode, \PDO::PARAM_INT);
        $stmt->bindParam(":usage_code",$usageCode, \PDO::PARAM_STR);

        $stmt->execute();
        $stmt = NULL;

        //Tx end
        $dbh->commit();
        $dbh = null;

    } catch (\Exception $ex) {

        $dbh->rollBack();
        $dbh = null;
        throw $ex ;
    }

    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->response = "lake create is success!" ;
    echo json_encode($responseObj) ;
    exit(0) ;


?>
