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
    $postData = json_decode($rawPostData) ;
    
    // check for file item.
    $fileItem = NULL ;

    if(!empty($postData)) {

        if(property_exists($postData, "fileUploadData") 
            && property_exists($fileUploadData, "items")) {

            $fileUploadData = $postData->fileUploadData  ;
            $fileItems = $fileUploadData->items ;
            if(sizeof($fileItems) > 0 ) {
                $fileItem = $fileItems[0] ;
            } 

            // upload error
            if(!$fileItem->upload) {
                $xmsg = sprintf("feature/update: file upload error: %s", $fileItem->error);
                Logger::getInstance()->error($xmsg);
                $fileItem = NULL ;
            }

        }
    }

    $dbh = NULL ;
   
    try {

        $dbh = PDOWrapper::getHandle();
        $featureId = $postData->featureId ;
        $serialNumber = $postData->sensor->serialNumber ;
        $featureObj = $postData->featureObj ;
        $sensorId = NULL ;

        // monitoring - sensor
        // see if sensor already exists 
        // yes - update/ otherwise insert a new one
        // add to feature <=> sensor mapping table 
        // add file_id into 
        // feature.lake_flow_file_id | feature.sensor_flow_file_id 
        // 
        // Tx start
        $dbh->beginTransaction();

        if($featureObj->monitoringCode == 1 ) {

            $row = Sensor::getOnSerialNumber($serialNumber);
            if(empty($row)) {
                // new sensor 
                $sensorId = Sensor::insert($dbh,$featureObj->sensor) ;
                Feature:addSensor($dbh,$featureId, $sensorId);
            } else {
                Sensor::updateOnSerialNumber($dbh, $featureObj->sensor) ;
            }
             
        }


        Feature::update($dbh,$featureObj,$fileItem.fileId);
        // Tx: end 
        $dbh->commit();
        $dbh = null;

    } catch (\Exception $ex) {

        $dbh->rollBack();
        $dbh = null;
        throw $ex ;
    }

    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->response = "lake feature update is success!" ;
    $responseObj->featureId = $featureId ;
    echo json_encode($responseObj) ;
    exit(0) ;


?>
