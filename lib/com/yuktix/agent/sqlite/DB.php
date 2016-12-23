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

        static function getDeviceSnapshot($dbh,$serialNumber) {
            
            $sql = " select * from device_snapshot where serial_num= :serial_num " ;
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":serial_num",$serialNumber, \PDO::PARAM_STR);
            $stmt->execute() ;
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ;
            return $rows ;

        }

        /*
        static function getDeviceChannels($dbh,$serialNumber) {

            $sql = " select * from device_snapshot where serial_num= :serial_num " ;
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":serial_num",$serialNumber, \PDO::PARAM_STR);
            $stmt->execute() ;
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ;
            return $rows ;

        }*/

        static function getDeviceChannels($dbh,$serialNumber) {

            $sql = " select * from device_channel where serial_num= :serial_num " ;
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

        static function updateDeviceChannel($dbh, $serialNumber, $channel) {

            $sql = "UPDATE DEVICE_CHANNEL SET CHANNEL_NAME = :name, CHANNEL_UNITS = :units " 
            . " where SERIAL_NUM = :serial_number and CHANNEL = :channel " ;

            $stmt = $dbh->prepare($sql);
            $name = empty($channel->name) ? $channel->code : $channel->name ;

            $stmt->bindParam(":name",$channel->name, \PDO::PARAM_STR);
            $stmt->bindParam(":units",$channel->units, \PDO::PARAM_STR);
            $stmt->bindParam(":serial_number",$serialNumber, \PDO::PARAM_STR);
            $stmt->bindParam(":channel",$channel->code, \PDO::PARAM_STR);

            $stmt->execute();
            $stmt = NULL;

        }

        static function insertDeviceChannel($dbh, $serialNumber, $channel) {
            
            $sql = "INSERT INTO DEVICE_CHANNEL(SERIAL_NUM,CHANNEL, CHANNEL_NAME, CHANNEL_UNITS)  " 
            . " VALUES(:serial_number, :channel, :name, :units)  " ;

            $stmt = $dbh->prepare($sql);
            $name = empty($channel->name) ? $channel->code : $channel->name ;

            $stmt->bindParam(":serial_number",$serialNumber, \PDO::PARAM_STR);
            $stmt->bindParam(":channel",$channel->code, \PDO::PARAM_STR);
            $stmt->bindParam(":name",$channel->name, \PDO::PARAM_STR);
            $stmt->bindParam(":units",$channel->units, \PDO::PARAM_STR);
           
            $stmt->execute();
            $stmt = NULL;

        }

    }

}


?>