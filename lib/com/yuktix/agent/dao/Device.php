<?php

namespace com\yuktix\agent\dao {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\yuktix\agent\sqlite\DB as DB ;


    class Device {

        static function getOnSerial($serialNumber) {

            
            $dsn = sprintf("sqlite:%s", Config::getInstance()->get_value("sqlite.db.path"));
            $dbh = new \PDO($dsn) or die("cannot open database");
            $row = DB::getDeviceOnSerial($dbh,$serialNumber) ;

            $device = new \stdClass ;
            $device->serialNumber = $row["SERIAL_NUM"] ;
            $device->location = $row["LOCATION"] ;
            $device->description = $row["DESCRIPTION"] ;
            
            $dbh = NULL ;

            return $device ;

        }

        static function update($device) { 

            $dsn = sprintf("sqlite:%s", Config::getInstance()->get_value("sqlite.db.path"));
            $dbh = new \PDO($dsn) or die("cannot open database");
            DB::updateDevice($dbh, $device);
            $dbh = NULL ;

        }

        static function getList() {

            // open sqlite connx
            // get entries from device_master table 
            // serial_num,  location, description, ping_time 
            // query device_snapshot with serial number
            // fetch serial_num + unix_ts  + channel + value 
            // translate each channel using device_channel table 
            // serial_num, channel, channel_name, part_number, channel_units 
            //  

            $dsn = sprintf("sqlite:%s", Config::getInstance()->get_value("sqlite.db.path"));
            $dbh = new \PDO($dsn) or die("cannot open database");

            $result = array();
            $deviceRows = DB::getDevices($dbh);
            $lookup = array() ;
            $icons = array(
                "T" => "wb_sunny",
                "RH" => "spa" ,
                "VOLTAGE" => "battery_charging_full"
            );

            foreach ($deviceRows as $deviceRow) { 
               

                $device =  new \stdClass ;
                $device->serialNumber = $deviceRow["SERIAL_NUM"];
                $device->description = empty($deviceRow["DESCRIPTION"]) ? "" : $deviceRow["DESCRIPTION"];
                $device->location = empty($deviceRow["LOCATION"])?  "Not set." : $deviceRow["LOCATION"];
                $device->channels = array() ;

                $lookup = array() ;
                $channelRows = DB::getDeviceChannels($dbh,$device->serialNumber);
                foreach($channelRows as $channelRow) {
                    $lookup[$channelRow["CHANNEL"]] = $channelRow ;
                }

                // latest device data 
                $snapshots  = DB::getDeviceSnapshot($dbh,$device->serialNumber);
                foreach($snapshots as $snapshot) {
                    
                    $channel = new \stdClass ;
                    $channel->tsUnix = $snapshot["UNIX_TS"];
                    $channel->code = $snapshot["CHANNEL"];
                    $channel->value = $snapshot["VALUE"];

                    if(array_key_exists($channel->code, $lookup)) {
                        $channel->name = $lookup[$code]["CHANNEL_NAME"];
                        $channel->units = $lookup[$code]["CHANNEL_UNITS"];
                    } else {
                        $channel->name = $channel->code ;
                        $channel->units = "" ;
                    }

                    $channel->icon = (array_key_exists($channel->code, $icons)) ? $icons[$channel->code] : "fiber_manual_record"; 
                    array_push($device->channels,$channel);

                }

                array_push($result, $device);
            }

            $dbh = NULL ;
            return $result  ;

        }

    }

}

?>