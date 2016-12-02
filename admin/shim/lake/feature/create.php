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

	function convert_feature_to_io_code($featureCode) {

        // @todo raise error for unknown feature code
        $mapping = array(
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 2 
        );

        return $mapping[$featureCode] ;

    }

	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    // PHP $_REQUEST only works for x-www-form-urlencoded content type
    // so we have to get the raw data when content-type is application/json 
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;

    $dbh = NULL ;

    try {

        $dbh = PDOWrapper::getHandle();
        // __EXCEPTION__ SQLSTATE[HY093]: 
        // Invalid parameter number: parameter was not defined
        // can be because of spelling mistakes in bind parameter names 
        // try to highlight each bind param.
        $sql = "insert INTO atree_lake_feature(name,lat,lon, max_height, width, " 
                . " feature_type_code, io_code, monitoring_code, lake_id, "
                . " created_on) VALUES (:name, :lat, :lon, :max_height, :width, "
                . ":feature_type_code, :io_code, :monitoring_code, :lake_id,"
                . " now())" ; 

        // Tx start
        $dbh->beginTransaction();
        $stmt = $dbh->prepare($sql);
        
        // bind params 
        // @todo input check for required params
        $iocode = convert_feature_to_io_code($postData->featureTypeCode);
        $maxHeight = intval($postData->height) ;
        $width = intval($postData->width) ;

        $stmt->bindParam(":name",$postData->name, \PDO::PARAM_STR);
        $stmt->bindParam(":lat",$postData->lat, \PDO::PARAM_STR);
        $stmt->bindParam(":lon",$postData->lon, \PDO::PARAM_STR);
        $stmt->bindParam(":max_height", $maxHeight, \PDO::PARAM_INT);
        $stmt->bindParam(":width", $width, \PDO::PARAM_INT);
      
        $stmt->bindParam(":feature_type_code",$postData->featureTypeCode, \PDO::PARAM_INT);
        $stmt->bindParam(":io_code",$iocode, \PDO::PARAM_INT);
        $stmt->bindParam(":monitoring_code",$postData->monitoringCode, \PDO::PARAM_INT);
        $stmt->bindParam(":lake_id",$postData->lakeId, \PDO::PARAM_STR);

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
    $responseObj->response = "lake feature creation is success!" ;
    echo json_encode($responseObj) ;
    exit(0) ;


?>
