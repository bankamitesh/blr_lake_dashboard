<?php

namespace com\yuktix\agent\dao {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\yuktix\agent\sqlite\DB as DB ;


    class Device {

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

            $response = new \stdClass ;
            $response->result = array();

            $deviceRows = DB::getDevices($dbh);
            $lookup = array() ;

            foreach ($deviceRows as $deviceRow) { 

                $device =  new \sdtClass ;
                $device->serialNumber = $deviceRow["serial_num"];
                $device->description = $deviceRow["description"];
                $device->location = $deviceRow["location"];
                $device->channels = array() ;

                $lookup = array() ;
                $channelRows = DB::getDeviceChannels($dbh,$device->serialNumber);
                foreach($channelRows as $channelRow) {
                    $lookup[$channelRow["channel"]] = $channelRow ;
                }

                // latest device data 
                $snapshots  = DB::getDeviceSnapshot($dbh,$device->serialNumber);
                foreach($snapshots as $snapshot) {
                    
                    $channel = new \stdClass ;
                    $channel->tsUnix = $snapshot["unix_ts"];
                    $channel->code = $snapshot["channel"];
                    $channel->value = $snapshot["value"];

                    if(array_key_exists($channel->code, $lookup)) {
                        $channel->name = $lookup[$code]["channel_name"];
                        $channel->units = $lookup[$code]["channel_units"];
                    } else {
                        $channel->name = $channel->code ;
                        $channel->units = "__null__" ;
                    }

                    array_push($device->channels,$channel);

                }

                array_push($response->result, $device);
            }

            $dbh = NULL ;
            return $response ;

        }

    }

}

?>