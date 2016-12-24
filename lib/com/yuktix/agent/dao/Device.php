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
            $device->channels = self::getChannels($serialNumber);
            
            
            $dbh = NULL ;
            return $device ;

        }

        static function update($device) { 

            $dsn = sprintf("sqlite:%s", Config::getInstance()->get_value("sqlite.db.path"));
            $dbh = new \PDO($dsn) or die("cannot open database");

            // @todo input check 
            DB::updateDevice($dbh, $device);

            $channels = $device->channels ;
            foreach($channels as $channel) {
                if($channel->db_flag == 1) { 
                    DB::updateDeviceChannel($dbh,$device->serialNumber,$channel);
                } else {
                    DB::insertDeviceChannel($dbh,$device->serialNumber,$channel);
                }
            }

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
                
                // latest device snapshot 
                $snapshots  = DB::getDeviceSnapshot($dbh,$device->serialNumber);
                foreach($snapshots as $snapshot) {
                    
                    $channel = new \stdClass ;
                    $channel->tsUnix = $snapshot["UNIX_TS"];
                    $channel->code = $snapshot["CHANNEL"];
                    $channel->value = $snapshot["VALUE"];

                    if(array_key_exists($channel->code, $lookup)) {
                        $channel->name = $lookup[$channel->code]["CHANNEL_NAME"];
                        $channel->units = $lookup[$channel->code]["CHANNEL_UNITS"];
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

        static function getChannels($serialNumber) {

            $dsn = sprintf("sqlite:%s", Config::getInstance()->get_value("sqlite.db.path"));
            $dbh = new \PDO($dsn) or die("cannot open database");

            $lookup = array() ;
            $results = array() ;

            // channels stored in device_channel table!
            $channelRows = DB::getDeviceChannels($dbh,$serialNumber);
            foreach($channelRows as $channelRow) {
                $lookup[$channelRow["CHANNEL"]] = $channelRow ;
            }

            // channels coming from actual nodes.
            $channels = DB::getDeviceSnapshot($dbh, $serialNumber);

            foreach ($channels as $channel) {

                $code = $channel["CHANNEL"]; 
                if(array_key_exists($code, $lookup)) {

                    // channel already in database!
                    $dataRow = $lookup[$code];
                    $item = new \stdClass ;
                    $item->db_flag = 1 ;
                    $item->code = $code ;
                    $item->name = $dataRow["CHANNEL_NAME"];
                    $item->units = $dataRow["CHANNEL_UNITS"];
                    
                    array_push($results, $item);

                } else { 

                    $item = new \stdClass ;
                    $item->code = $code ;
                    $item->db_flag = 0 ;
                    $item->name = $code;
                    $item->units = "" ;
                    array_push($results, $item);

                }  

            }

            return $results ;

        }

        static function getChannelCodes($serialNumber) {

            $dsn = sprintf("sqlite:%s", Config::getInstance()->get_value("sqlite.db.path"));
            $dbh = new \PDO($dsn) or die("cannot open database");

            $result = array() ;
            // channels coming from actual nodes.
            $channels = DB::getDeviceSnapshot($dbh, $serialNumber);

            foreach ($channels as $channel) {
                $code = $channel["CHANNEL"]; 
                array_push($results, $code);

            }

            $dbh = NULL ;
            return $results ;

        }
        
        static function getPlotData($serialNumber) {

            $results = array() ;

            $channels = self::getChannels($serialNumber);
            foreach ($channels as $channel) {
                
                $plot = new \stdClass ;
                // get series for the code 
                $plot->series = self::getTimeSeries($serialNumber, $channel->code);
                $plot->name = $channel->name ;
                $plot->at_human = 0 ;
                $plot->current_value = 0 ;
                $plot->units = $channel->units ;
                $plot->renderer = "line" ;
                $plot->tick = "5minute" ;

                array_push($results,$plot);

            }

            return $results; 

        }

        static function getTimeSeries($serialNumber, $channel) {

            $dsn = sprintf("sqlite:%s", Config::getInstance()->get_value("sqlite.db.path"));
            $dbh = new \PDO($dsn) or die("cannot open database");
            $rows = DB::getTimeSeries($dbh,$serialNumber, $channel);
            $results = array() ;

            foreach($rows as $row) {
                $item = new \stdClass ;
                $item->x = intval($row["UNIX_TS"]);
                $item->y = intval($row["VALUE"]);
                array_push($results, $item);
            }

            $dbh = NULL ;
            return $results ;


        }

    }

}

?>