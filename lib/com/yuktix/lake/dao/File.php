<?php

namespace com\yuktix\lake\dao {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\indigloo\mysql as MySQL;
    use \com\indigloo\mysql\PDOWrapper;
    use \com\indigloo\exception\UIException as UIException;

    use \com\yuktix\lake\api\Response as Response ;
    use \com\yuktix\lake\mysql\Lake as LakeDB ;
    use \com\yuktix\lake\Constants as LakeConstants ;
    

    class File {

        static function getFeatureDataPreview($fileIds) {

             if(!is_array($fileIds) || (sizeof($fileIds) > 2)) {
                $xmsg = "error: wrong number of files in upload!! max. limit is 2" ;
                trigger_error($xmsg, E_USER_ERROR);
            }

            $calibration_count = 0 ;
            $data_count = 0 ;
            
            $data = new \stdClass ;
            $data->snapshots = array() ;
            $data->errors = array() ;

            if(Config::getInstance()->is_debug()) {
                Logger::getInstance()->debug("feature data preview: fileIds:: ");
                Logger::getInstance()->dump($fileIds);
            }

            foreach($fileIds as $fileId) {

                $preview = self::parseCSVBlob($fileId, array("limit" => 4));

                if(Config::getInstance()->is_debug()) {
                    Logger::getInstance()->debug("feature data preview:: ");
                    Logger::getInstance()->dump($preview);
                }

                array_push($data->snapshots, $preview);

                switch($preview->fileCode) {

                    case LakeConstants::CALIBRATION_FILE :
                        $calibration_count++ ;
                        break ;
                    case LakeConstants::DATA_FILE :
                        $data_count++ ;
                        break ;
                    default :
                        break ;
                        
                }
                
                if($preview->columns < 2 ) {
                    $xmsg = "error: file with zero or one column found!" ;
                    array_push($data->errors, $xmsg) ;
                }
                
            }

            if($calibration_count > 1 ) {
                $xmsg = "error: more than one calibration file found!" ;
                array_push($data->errors, $xmsg) ;
            }

            if($data_count == 0 ) {
                $xmsg = "error: no data file found!" ;
                array_push($data->errors, $xmsg) ;
            }

            return $data ;

        }

        static function array_to_line($parts) {
            $line = "" ;
            $count =  sizeof($parts) ;
            for($i = 0 ; $i < $count ; $i++ )  {
                $line = ($i == 0) ? ($line. $parts[$i]) : ($line. ", ".$parts[$i])  ;
            }

            return $line ;
            
        }

        static function getContourData($fileId) {

            $options = array("split_line" => true);
            $result = self::parseCSVBlob($fileId, $options);
            $count = $result->count ;

            $count  = intval(sqrt($result->count)) ;
            Logger::getInstance()->error("count=".$count);

            // input check 
            // count should be full integer 
            // number of colums: 3 

            $xarr = array() ;
            $yarr= array() ;
            $zarr = array() ;
            $row = array() ;
            $total = 0 ;
            $i = 0 ;
            $j = 0 ;

            for($i = 0 ; $i < $count; $i++) {

                $fns = array() ;
                $row = $result->rows[$i];
                array_push($xarr, $row[0]) ;

                for($j = 0 ; $j < $count; $j++) {
                    Logger::getInstance()->error("total=".$total);
                    $row = $result->rows[$total];
                    if($j == 0 ) {
                        array_push($yarr, $row[1]) ;
                    }

                    array_push($fns, $row[2]);
                    $total++ ;

                }

                 array_push($zarr, $fns);

            }

            $data = array();
            $data = array(
                "x" => $xarr,
                "y" => $yarr,
                "z" => $zarr 
            );

            return $data ;
            
        }
        
        /*
         * $options  = array( "limit" => 4, "split_line" => true ) ;
         * 
         */
        
        static function parseCSVBlob($fileId, $options=array()) {

            // default is not to split line in parts 
            // no limits 
            // 
            $defaults = array(
                "split_line" => false
            );

            $settings = array_merge($defaults,$options);
            $mysqli = MySQL\Connection::getInstance()->getHandle();
    
            // get BLOB 
            $stmt = $mysqli->prepare("select file_blob from atree_file_blob WHERE id= ?"); 
            $stmt->bind_param("i", $fileId);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($blob);
            $stmt->fetch();

            // parse CSV BLOB 
            $count = 0 ;
            $result = new \stdClass ;
            $result->rows = array() ;
            
            $numeric_col = 0 ;
            $date_col = 0 ;
            $num_parts = 0 ;

            $lines = explode(PHP_EOL, $blob);
            foreach($lines as $line) {

                $num_parts = 0 ;
                $line = trim($line);
                // line empty or does not contains comma?
                if(empty($line)) {
                    continue ;
                }

                $parts = explode("," , $line) ;
                $num_parts = sizeof($parts) ;
                $fixed_parts = array() ;

                // reset column counter 
                $numeric_col = 0 ;
                $date_col = 0 ;
                
                for( $i = 0 ; $i < $num_parts; $i++  ) {

                    // remove spaces
                    $value = trim($parts[$i]) ;
                    if(is_numeric($value)) {
                        $value = sprintf("%.2f", $value);
                        $value = floatval ($value);
                        $numeric_col = $numeric_col + 1  ;
                    }

                    if(strpos($value, "/") !== FALSE) {
                        // is this date string? 
                        $date = \DateTime::createFromFormat('j/m/Y', $value) ;
                        if($date !== FALSE) {
                            $date_col = $date_col + 1 ;
                        }
                    }

                    if(strpos($value, ":") !== FALSE) {
                        $date = \DateTime::createFromFormat('j/m/Y H:i', "22/09/2016 ".$value) ;
                        if($date !== FALSE) {
                            $date_col = $date_col + 1 ;
                        }
                    }

                    array_push($fixed_parts, $value) ;
                }


                if(array_key_exists("split_line", $settings)) {
                    if($settings["split_line"]) {
                        array_push($result->rows, $fixed_parts);
                    } else {
                        array_push($result->rows, self::array_to_line($fixed_parts));
                    }

                }

                $count = $count + 1 ;
                if($count == 1 ){
                    $num_parts = sizeof($parts);

                }

                if(array_key_exists("limit", $settings)) {
                    $limit = intval($options["limit"]);
                    if($count >= $limit) {
                        break ;
                    }
                }
            }

            // csv parser response 
            $result->count = $count ;
            $result->columns = $num_parts ;
            $result->dateColumns = $date_col ;
            $result->numericColumns = $numeric_col ;
            $result->fileId = $fileId ;
            $result->fileCode = self::getCSVFileType( $result->dateColumns, $result->columns) ;
            return $result ;
           
        }

        static function getCSVFileType($numParts, $dateColumns) {

            $tof = LakeConstants::UNKNOWN_FILE ;

            // guess file type
            if( ($dateColumns == 0) && ($numParts == 2)) {
                return LakeConstants::CALIBRATION_FILE ;
            }

            if ( ($dateColumns == 2) && ($numParts == 3) ) {
                return LakeConstants::DATA_FILE ;
            }

        }

    }

}

?>