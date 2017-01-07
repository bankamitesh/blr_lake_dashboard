<?php

namespace com\yuktix\lake\mysql {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\indigloo\mysql as MySQL;

    use \com\yuktix\lake\mysql\Sensor as Sensor ;
    use \com\yuktix\lake\api\Response as Response ;
    use \com\indigloo\util\StringUtil as StringUtil ;

    class Feature {

        static function convertFeatureToIOCode($featureCode) {
            // @todo raise error for unknown feature code
            $mapping = array(
                1 => 1,
                2 => 1,
                3 => 1,
                4 => 2 
            );

            return $mapping[$featureCode] ;
        }

        static function createFeatureObject($row) {
            
            $featureObj = new \stdClass ;
            if(empty($row)) {
                return $featureObj ;
            }

            $featureObj->name = $row["name"];
            $featureObj->lat = $row["lat"] ;
            $featureObj->lon = $row["lon"] ;
            $featureObj->maxHeight = $row["max_height"] ;
            $featureObj->width = $row["width"] ;

            // @todo change codes to strings?
            $featureObj->iocode = intval($row["io_code"]);
            $featureObj->featureTypeCode = intval($row["feature_type_code"]);
            $featureObj->monitoringCode = intval($row["monitoring_code"]);

            $featureObj->id = $row["id"];
            $featureObj->lakeId = $row["lake_id"];

            $flowRate = $row["flow_rate"];
            $flowRate = empty($flowRate) ? "" : $flowRate ;
            $featureObj->flowRate = $flowRate;
            // @todo turn file_id into full links for download
            // lake_flow_file_id
            // sensor_flow_file_id 
            $featureObj->sensor = json_decode($row["sensor_data"]);
            return $featureObj ;

        }

        static function getOnId($featureId) {

            // input check
            if(empty($featureId)) {
                $xmsg = "required parameter featureId is missing" ;
                Response::raiseBadInputError($xmsg);
            }

            $mysqli = MySQL\Connection::getInstance()->getHandle();
            $featureId = $mysqli->real_escape_string($featureId);

            $sql = " select * from atree_lake_feature where id = ".$featureId ;
            $row = MySQL\Helper::fetchRow($mysqli, $sql);
            $featureObj = self::createFeatureObject($row) ;

            // release mysqli resources
            // @imp: this should never be done inside DAO or DB 
            // layers as the mysqli connection is shared by all calls on 
            // same page. The calling script should release the resources 
            // at the end.  
            // MySQL\Connection::getInstance()->closeHandle() ;
            return $featureObj ;
        }

         static function getAllFeatures($lakeId) {

            // input check
            if(empty($lakeId)) {
                $xmsg = "required parameter lakeId is missing" ;
                Response::raiseBadInputError($xmsg);
            }

            $result = array() ;
            $mysqli = MySQL\Connection::getInstance()->getHandle();
            $lakeId = $mysqli->real_escape_string($lakeId);

            $sql = " select * from atree_lake_feature where lake_id = ".$lakeId ;
            $rows = MySQL\Helper::fetchRows($mysqli, $sql);

             foreach ($rows as $row) {
                $feature = self::createFeatureObject($row) ;
                array_push($result, $feature);
            }

            
            return $result ;

        }
        
        static function insert($dbh, $featureObj) {

            // __EXCEPTION__ SQLSTATE[HY093]: 
            // Invalid parameter number: parameter was not defined
            // can be because of spelling mistakes in bind parameter names 
            // try to highlight each bind param.
            $sql = "insert INTO atree_lake_feature(name,lat,lon, max_height, width, " 
                    . " feature_type_code, io_code, monitoring_code, lake_id, sensor_data,"
                    . " created_on) VALUES (:name, :lat, :lon, :max_height, :width, "
                    . ":feature_type_code, :io_code, :monitoring_code, :lake_id, :sensor_data,"
                    . " now())" ; 
            
            // @todo input check 
            $stmt = $dbh->prepare($sql); 
            $iocode = self::convertFeatureToIOCode($featureObj->featureTypeCode);
            $maxHeight = intval($featureObj->maxHeight) ;
            $width = intval($featureObj->width) ;
            $sensorData = "{}" ;

            $stmt->bindParam(":name",$featureObj->name, \PDO::PARAM_STR);
            $stmt->bindParam(":lat",$featureObj->lat, \PDO::PARAM_STR);
            $stmt->bindParam(":lon",$featureObj->lon, \PDO::PARAM_STR);
            $stmt->bindParam(":max_height", $maxHeight, \PDO::PARAM_INT);
            $stmt->bindParam(":width", $width, \PDO::PARAM_INT);
        
            $stmt->bindParam(":feature_type_code",$featureObj->featureTypeCode, \PDO::PARAM_INT);
            $stmt->bindParam(":io_code",$iocode, \PDO::PARAM_INT);
            $stmt->bindParam(":monitoring_code",$featureObj->monitoringCode, \PDO::PARAM_INT);
            $stmt->bindParam(":lake_id",$featureObj->lakeId, \PDO::PARAM_STR);
            $stmt->bindParam(":sensor_data",$sensorData, \PDO::PARAM_STR);

            $stmt->execute();
            // remember lastInsertId is a function!
            $featureId = $dbh->lastInsertId() ;
            $stmt = NULL ;

            return $featureId ;

        }

        static function update($dbh, $featureObj,$fileId) {

            // @todo : input check
            // monitoring - sensor
            // see if sensor already exists 
            // yes - update/ otherwise insert a new one
            // add to feature <=> sensor mapping table 
            // add file_id into 
            // feature.lake_flow_file_id | feature.sensor_flow_file_id 
            // 
            $serialNumber = NULL ;
            $sensorData = "{}" ;
            $featureId = $featureObj->id  ;

            if($featureObj->monitoringCode == 1 ) {

                $sensorData = json_encode($featureObj->sensor);
                $serialNumber = $featureObj->sensor->serialNumber ; 
                $row = Sensor::getOnSerialNumber($serialNumber);
                if(empty($row)) {
                    // new sensor 
                    $sensorId = Sensor::insert($dbh,$featureObj->sensor) ;
                    self::addSensor($dbh,$featureId, $sensorId);
                } else {
                    Sensor::updateOnSerialNumber($dbh, $featureObj->sensor) ;
                }
                
            }
            
            $sql = "update  atree_lake_feature set name=:name, lat=:lat," 
                ." lon = :lon, max_height = :max_height, width = :width, feature_type_code = :feature_type_code," 
                ." io_code = :io_code, monitoring_code = :monitoring_code, lake_id = :lake_id,"
                ." flow_rate = :flow_rate, lake_flow_file_id = :lake_flow_file_id, "
                ." sensor_flow_file_id = :sensor_flow_file_id, sensor_data = :sensor_data, " 
                . " updated_on = now() where id=:id "  ; 
            
            $stmt = $dbh->prepare($sql);
            
            $iocode = self::convertFeatureToIOCode($featureObj->featureTypeCode);
            $maxHeight = intval($featureObj->maxHeight) ;
            $width = intval($featureObj->width) ;

            // correct fileId
            $sensorFlowFileId = ($featureObj->monitoringCode == 1 ) ? $fileId : 0 ;
            $lakeFlowFileId = ($featureObj->monitoringCode == 2 ) ? $fileId : 0 ;
    
            $stmt->bindParam(":name",$featureObj->name, \PDO::PARAM_STR);
            $stmt->bindParam(":lat",$featureObj->lat, \PDO::PARAM_STR);
            $stmt->bindParam(":lon",$featureObj->lon, \PDO::PARAM_STR);
            $stmt->bindParam(":max_height", $maxHeight, \PDO::PARAM_INT);
            $stmt->bindParam(":width", $width, \PDO::PARAM_INT);
        
            $stmt->bindParam(":feature_type_code",$featureObj->featureTypeCode, \PDO::PARAM_INT);
            $stmt->bindParam(":io_code",$iocode, \PDO::PARAM_INT);
            $stmt->bindParam(":monitoring_code",$featureObj->monitoringCode, \PDO::PARAM_INT);
            $stmt->bindParam(":lake_id",$featureObj->lakeId, \PDO::PARAM_STR);

            $stmt->bindParam(":flow_rate",$featureObj->flowRate, \PDO::PARAM_STR);
            $stmt->bindParam(":lake_flow_file_id",$lakeFlowFileId, \PDO::PARAM_STR);
            $stmt->bindParam(":sensor_flow_file_id",$sensorFlowFileId, \PDO::PARAM_STR);
            $stmt->bindParam(":sensor_data",$sensorData, \PDO::PARAM_STR);
            $stmt->bindParam(":id",$featureObj->id, \PDO::PARAM_STR);
            

            $stmt->execute();
            $stmt = NULL ;
            return ;

        }

        static private function addSensor($dbh,$featureId, $sensorId) {
            
            $sql = "insert INTO atree_feature_sensor(feature_id, sensor_id,created_on) " 
                    . " VALUES (:feature_id, :sensor_id, now()) " ;
            
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":feature_id",$featureId, \PDO::PARAM_INT);
            $stmt->bindParam(":sensor_id",$sensorId, \PDO::PARAM_INT);
            $stmt->execute();
            $stmt = NULL ;
            return ;
         
        }

        static function storeData(
                    $dbh,
                    $lakeId,
                    $featureId,
                    $ioCode,
                    $fileId,
                    $calibrationFileId ) {

            $sql = "insert into atree_feature_file (lake_id, feature_id, io_code, " 
            . " file_id, calibration_file_id, op_code, created_on) "
            . " values (:lake_id, :feature_id, :io_code, :file_id, " 
            . " :calibration_file_id, :op_code, now() ) " ;

            $opCode = 1 ;
            $stmt = $dbh->prepare($sql);

            $stmt->bindParam(":lake_id",$lakeId, \PDO::PARAM_INT);
            $stmt->bindParam(":feature_id",$featureId, \PDO::PARAM_INT);
            $stmt->bindParam(":io_code",$ioCode, \PDO::PARAM_INT);
            $stmt->bindParam(":file_id",$fileId, \PDO::PARAM_INT);
            $stmt->bindParam(":calibration_file_id",$calibrationFileId, \PDO::PARAM_INT);
            $stmt->bindParam(":op_code",$opCode, \PDO::PARAM_INT);

            $stmt->execute();
            $stmt = NULL ;
            return ;
            
        }

    }

}


?>