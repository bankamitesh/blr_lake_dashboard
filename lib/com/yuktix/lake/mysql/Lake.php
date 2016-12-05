<?php

namespace com\yuktix\lakeObj\mysql {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\indigloo\mysql as MySQL;

    use \com\indigloo\util\StringUtil as StringUtil ;
    use \com\yuktix\lake\api\Response as Response ;

    class Lake {

        static function createlakeObjObject($row) {

            $lakeObj = new \stdClass ;
            if(empty($row)) {
                return $lakeObj ;
            }

            $lakeObj->name = $row["name"];
            $lakeObj->about = $row["about"];
            $lakeObj->lat = $row["lat"] ;
            $lakeObj->lon = $row["lon"] ;

            $lakeObj->address = $row["address"] ;
            $lakeObj->maxArea = $row["max_area"] ;
            $lakeObj->maxVolume = $row["max_volume"] ;
            $lakeObj->rechargeRate = $row["recharge_rate"] ;

            $lakeObj->typeCode = intval($row["type_code"]);
            $lakeObj->agencyCode = intval($row["agency_code"]);
            $lakeObj->usageCode = [] ;
            $dbUsageCodes = json_decode($row["usage_code"]);
            foreach ($dbUsageCodes as $dbUsageCode) {
                array_push($lakeObj->usageCode, intval($dbUsageCode));
            }

            $lakeObj->id = $row["id"];
            return $lakeObj ;
        }

        static function insert($dbh,$lakeObj) {

             // @todo error check for required params
             $sql = "insert INTO atree_lake(name, cname,about,lat,lon,address, max_area, " 
                . " max_volume, recharge_rate, agency_code, type_code, usage_code, "
                . " created_on) VALUES (:name, :cname, :about, :lat, :lon, :address, "
                . ":max_area, :max_volume, :recharge_rate, :agency_code, :type_code, :usage_code, "
                . " now())" ; 

                $stmt = $dbh->prepare($sql);
                $cname = StringUtil::convertNameToKey($lakeObj->name) ;
                $usageCode = json_encode($lakeObj->usageCode);

                $stmt->bindParam(":name",$lakeObj->name, \PDO::PARAM_STR);
                $stmt->bindParam(":cname",$cname, \PDO::PARAM_STR);
                $stmt->bindParam(":about",$lakeObj->about, \PDO::PARAM_STR);
                $stmt->bindParam(":lat",$lakeObj->lat, \PDO::PARAM_STR);
                $stmt->bindParam(":lon",$lakeObj->lon, \PDO::PARAM_STR);
                $stmt->bindParam(":address",$lakeObj->address, \PDO::PARAM_STR);
                $stmt->bindParam(":max_area",$lakeObj->maxArea, \PDO::PARAM_STR);
                $stmt->bindParam(":max_volume",$lakeObj->maxVolume, \PDO::PARAM_STR);
                $stmt->bindParam(":recharge_rate",$lakeObj->rechargeRate, \PDO::PARAM_STR);
                $stmt->bindParam(":agency_code",$lakeObj->agencyCode, \PDO::PARAM_INT);
                $stmt->bindParam(":type_code",$lakeObj->typeCode, \PDO::PARAM_INT);
                $stmt->bindParam(":usage_code",$usageCode, \PDO::PARAM_STR);

                $stmt->execute();
                $stmt = NULL;

        }

        static function update ($dbh, $lakeObj) {

            // @todo input check 
             $sql = " update atree_lake set name = :name, cname = :cname, about = :about, "
                ." lat = :lat, lon =:lon ,address = :address, max_area = :max_area, " 
                ." max_volume = :max_volume, recharge_rate = :recharge_rate, "
                ." agency_code= :agency_code, type_code = :type_code, usage_code = :usage_code, "
                ." updated_on = now() where id = :id ";
           
                
            $stmt = $dbh->prepare($sql);
            $cname = StringUtil::convertNameToKey($lakeObj->name) ;
            $usageCode = json_encode($lakeObj->usageCode);
        
            if(Config::getInstance()->is_debug()){ 
                Logger::getInstance()->debug($usageCode);
            }

            $stmt->bindParam(":name",$lakeObj->name, \PDO::PARAM_STR);
            $stmt->bindParam(":cname",$cname, \PDO::PARAM_STR);
            $stmt->bindParam(":about",$lakeObj->about, \PDO::PARAM_STR);
            $stmt->bindParam(":lat",$lakeObj->lat, \PDO::PARAM_STR);
            $stmt->bindParam(":lon",$lakeObj->lon, \PDO::PARAM_STR);
            $stmt->bindParam(":address",$lakeObj->address, \PDO::PARAM_STR);
            $stmt->bindParam(":max_area",$lakeObj->maxArea, \PDO::PARAM_STR);
            $stmt->bindParam(":max_volume",$lakeObj->maxVolume, \PDO::PARAM_STR);
            $stmt->bindParam(":recharge_rate",$lakeObj->rechargeRate, \PDO::PARAM_STR);
            $stmt->bindParam(":agency_code",$lakeObj->agencyCode, \PDO::PARAM_INT);
            $stmt->bindParam(":type_code",$lakeObj->typeCode, \PDO::PARAM_INT);
            $stmt->bindParam(":usage_code",$usageCode, \PDO::PARAM_STR);
            $stmt->bindParam(":id",$lakeObj->id, \PDO::PARAM_STR);

            $stmt->execute();
            $stmt = NULL;

        }

        static function getOnId($lakeId) {

            // input check
            if(empty($lakeId)) {
                $xmsg = "required parameter lakeId is missing";
                Response::raiseBadInputError($xmsg) ;
            }

            $mysqli = MySQL\Connection::getInstance()->getHandle();
            $lakeId = $mysqli->real_escape_string($lakeId);
            $sql = " select * from atree_lake where id = ".$lakeId ;
            $row = MySQL\Helper::fetchRow($mysqli, $sql);
            $lakeObj = self::createlakeObjObject($row) ;
            
            return $lakeObj ;

        }
        
        static function getAllLakes() {

            $result = array() ;
            $mysqli = MySQL\Connection::getInstance()->getHandle();
            $sql = " select * from atree_lake " ;
            $rows = MySQL\Helper::fetchRows($mysqli, $sql);
            
            foreach ($rows as $row) {
                $lake = self::createLakeObject($row) ;
                array_push($result, $lake);
            }

            return $result ;
        }

    }

}


?>