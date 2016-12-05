<?php

namespace com\yuktix\lake\mysql {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\indigloo\mysql as MySQL;
    use \com\yuktix\lake\api\Response as Response ;
    use \com\indigloo\util\StringUtil as StringUtil ;

    class Feature {

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

            $featureObj->iocode = intval($row["io_code"]);
            $featureObj->featureTypeCode = intval($row["feature_type_code"]);
            $featureObj->monitoringCode = intval($row["monitoring_code"]);
            $featureObj->id = $row["id"];
            $featureObj->lakeId = $row["lake_id"];

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
            return $featureObj ;
        }

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

        static function insert($dbh, $featureObj) {

            // __EXCEPTION__ SQLSTATE[HY093]: 
            // Invalid parameter number: parameter was not defined
            // can be because of spelling mistakes in bind parameter names 
            // try to highlight each bind param.
            $sql = "insert INTO atree_lake_feature(name,lat,lon, max_height, width, " 
                    . " feature_type_code, io_code, monitoring_code, lake_id, "
                    . " created_on) VALUES (:name, :lat, :lon, :max_height, :width, "
                    . ":feature_type_code, :io_code, :monitoring_code, :lake_id,"
                    . " now())" ; 
            
            $stmt = $dbh->prepare($sql);
        
            // bind params 
            // @todo input check for required params
            // @todo unique constraint checks
            // @todo - placeholders for missing data 
            $iocode = self::convertFeatureToIOCode($featureObj->featureTypeCode);
            $maxHeight = intval($featureObj->maxHeight) ;
            $width = intval($featureObj->width) ;

            $stmt->bindParam(":name",$featureObj->name, \PDO::PARAM_STR);
            $stmt->bindParam(":lat",$featureObj->lat, \PDO::PARAM_STR);
            $stmt->bindParam(":lon",$featureObj->lon, \PDO::PARAM_STR);
            $stmt->bindParam(":max_height", $maxHeight, \PDO::PARAM_INT);
            $stmt->bindParam(":width", $width, \PDO::PARAM_INT);
        
            $stmt->bindParam(":feature_type_code",$featureObj->featureTypeCode, \PDO::PARAM_INT);
            $stmt->bindParam(":io_code",$iocode, \PDO::PARAM_INT);
            $stmt->bindParam(":monitoring_code",$featureObj->monitoringCode, \PDO::PARAM_INT);
            $stmt->bindParam(":lake_id",$featureObj->lakeId, \PDO::PARAM_STR);

            $stmt->execute();
            // remember lastInsertId is a function!
            $featureId = $dbh->lastInsertId() ;
            return $featureId ;

        }

        static function update($dbh, $featureObj,$fileId) {

            // @todo : input check
            $sql = "update  atree_lake_feature set name=:name, lat=:lat," 
                ." lon = :lon, max_height = :max_height, width = :width, feature_type_code = :feature_type_code," 
                ." io_code = :io_code, monitoring_code = :monitoring_code, lake_id = :lake_id,"
                ." flow_rate = :flow_rate, lake_flow_file_id = :lake_flow_file_id, "
                ." sensor_flow_file_id = :sensor_flow_file_id, updated_on = now() where id=:id "  ; 
            
            $stmt = $dbh->prepare($sql);
            
            $iocode = self::convertFeatureToIOCode($featureObj->featureTypeCode);
            $maxHeight = intval($featureObj->maxHeight) ;
            $width = intval($featureObj->width) ;

            // correct fileId
            $sensorFlowFileId = ($featureObj->monitoringCode == 1 ) ? $fileId : "" ;
            $lakeFlowFileId = ($featureObj->monitoringCode == 2 ) ? $fileId : "" ;
    
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
            $stmt->bindParam(":id",$featureObj->id, \PDO::PARAM_STR);
            

            $stmt->execute();
            return ;

        }

        static function addSensor($dbh,$featureId, $sensorId) {
            
            $sql = "insert INTO atree_feature_sensor(feature_id, sensor_id,created_on) " 
                    . " VALUES (:feature_id, :sensor_id, now()) " ;
            
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":feature_id",$featureId, \PDO::PARAM_INT);
            $stmt->bindParam(":sensor_id",$sensorId, \PDO::PARAM_INT);
            $stmt->execute();
            return ;
         
        }

    }

}


?>