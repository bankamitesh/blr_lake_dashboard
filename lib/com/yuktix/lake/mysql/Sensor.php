<?php

namespace com\yuktix\lake\mysql {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\indigloo\mysql as MySQL;
    use \com\indigloo\util\StringUtil as StringUtil ;
    use \com\yuktix\lake\api\Response as Response ;

    class Sensor {

        static function getOnSerialNumber($serialNumber) {

            $mysqli = MySQL\Connection::getInstance()->getHandle();
            $serialNumber = $mysqli->real_escape_string($serialNumber);

            $sql = " select * from atree_sensor where serial_number = '".$serialNumber. "' " ;
            $row = MySQL\Helper::fetchRow($mysqli, $sql);
            return $row ;

        }

        static function insert($dbh, $sensorObj) {

            $sql = "insert INTO atree_sensor(serial_number, part_number, installer_name, "
                . " installation_date, created_on) "
                . " VALUES (:serial_number, :part_number, " 
                . " :installer_name, :installation_date, now()) "  ;


            $stmt = $dbh->prepare($sql);
            // bind params 
            $stmt->bindParam(":serial_number",$sensorObj->serialNumber, \PDO::PARAM_STR);
            $stmt->bindParam(":part_number",$sensorObj->partNumber, \PDO::PARAM_STR);
            $stmt->bindParam(":installer_name",$sensorObj->installerName, \PDO::PARAM_STR);
            $stmt->bindParam(":installation_date", $sensorObj->installationDate, \PDO::PARAM_STR);
            
            $stmt->execute();
            $sensorId = $dbh->lastInsertId() ;
            return $sensorId ;

        }

         static function updateOnSerialNumber($dbh, $sensorObj) {

            $sql = "update atree_sensor set "
                . " part_number = :part_number, installer_name = :installer_name, "
                . " installation_date = :installation_date , updated_on = now()  "
                . " where serial_number = :serial_number ";
            
            $stmt = $dbh->prepare($sql);
            // bind params 
            $stmt->bindParam(":serial_number",$sensorObj->serialNumber, \PDO::PARAM_STR);
            $stmt->bindParam(":part_number",$sensorObj->partNumber, \PDO::PARAM_STR);
            $stmt->bindParam(":installer_name",$sensorObj->installerName, \PDO::PARAM_STR);
            $stmt->bindParam(":installation_date", $sensorObj->installationDate, \PDO::PARAM_STR);
            
            $stmt->execute();
            $sensorId = $dbh->lastInsertId() ;
            return $sensorId ;

        }


    }

}


?>