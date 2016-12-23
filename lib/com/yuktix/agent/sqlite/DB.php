<?php

namespace com\yuktix\agent\sqlite {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    

    class DB {

        

        static function getDevices($dbh) {
            
            $sql = " select * from device_master " ;
            $stmt = $dbh->prepare($sql);
            $stmt->execute() ;
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ;
            return $rows ;
         
        }

        static function getDeviceChannels($dbh,$serialNumber) {

            $sql = " select channel from device_snapshot where serial_num= :serial_num " ;
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":serial_num",$serialNumber, \PDO::PARAM_STR);
            $stmt->execute() ;
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ;
            return $rows ;

        }

        static function getChannelData($dbh,$serialNumber) {

            $sql = " select channel from device_channel where serial_num= :serial_num " ;
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":serial_num",$serialNumber, \PDO::PARAM_STR);
            $stmt->execute() ;
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ;
            return $rows ;

        }

        static function getDeviceSnapshot($dbh,$serialNumber) {
            
            $sql = " select * from device_snapshot where serial_num= :serial_num " ;
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":serial_num",$serialNumber, \PDO::PARAM_STR);
            $stmt->execute() ;
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ;
            return $rows ;

        }

        static function getDeviceOnSerial($dbh, $serialNumber) { 

            $sql = " select * from device_master where serial_num = :serial_num " ;
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":serial_num",$serialNumber, \PDO::PARAM_STR);
            $stmt->execute() ;
            $row = $stmt->fetch(\PDO::FETCH_ASSOC) ;
            return $row ;

        }

        static function updateDevice($dbh, $device) { 

            $sql = "UPDATE DEVICE_MASTER SET DESCRIPTION = :description, LOCATION = :location " 
            . " where SERIAL_NUM = :serial_number " ;

            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":description",$device->description, \PDO::PARAM_STR);
            $stmt->bindParam(":location",$device->location, \PDO::PARAM_STR);
            $stmt->bindParam(":serial_number",$device->serialNumber, \PDO::PARAM_STR);

            $stmt->execute();
            $stmt = NULL;

        }


    }

}


?>