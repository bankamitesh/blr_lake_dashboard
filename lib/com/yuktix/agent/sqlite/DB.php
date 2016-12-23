<?php

namespace com\yuktix\agent\sqlite {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    

    class DB {

        

        static function getDevices($dbh) {
            
            $sql = " select * from device_master " ;
            $stmt = $dbh->prepare($sql);
            $stmt->execute() ;
            $rows = $stmt->fetchAll() ;
            return $rows ;
         
        }

        static function getDeviceChannels($dbh,$serialNumber) {

            $sql = " select * from device_channel where serial_num= :serial_num " ;
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":serial_num",$serialNumber, \PDO::PARAM_STR);
            $stmt->execute() ;
            $rows = $stmt->fetchAll() ;
            return $rows ;

        }

        static function getDeviceSnapshot($dbh,$serialNumber) {
            
            $sql = " select * from device_snapshot where serial_num= :serial_num " ;
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":serial_num",$serialNumber, \PDO::PARAM_STR);
            $stmt->execute() ;
            $rows = $stmt->fetchAll() ;
            return $rows ;

        }

    }

}


?>