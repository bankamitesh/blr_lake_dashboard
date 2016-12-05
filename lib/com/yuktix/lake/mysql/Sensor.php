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

        static function create($dbh, $sensorData) {

            $sql1 = "insert INTO atree_sensor(serial_number, part_number, installer_name, "
                . " installation_date, created_on) "
                . " VALUES (:serial_number, :part_number, " 
                . " :installer_name, :installation_date, now()) "  ;


            $stmt1 = $dbh->prepare($sql1);
            // bind params 
            $stmt1->bindParam(":serial_number",$sensorData->serialNumber, \PDO::PARAM_STR);
            $stmt1->bindParam(":part_number",$sensorData->partNumber, \PDO::PARAM_STR);
            $stmt1->bindParam(":installer_name",$sensorData->installerName, \PDO::PARAM_STR);
            $stmt1->bindParam(":installation_date", $sensorData->installationDate, \PDO::PARAM_STR);
            
            $stmt1->execute();
            $sensorId = $dbh->lastInsertId() ;
            return $sensorId ;

        }
    }

}


?>