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

            $calibration_count = 0 ;
            $data_count = 0 ;

            $data = new \stdClass ;
            $data->previews = array() ;
            $data->errors = array() ;

            foreach($fileIds as $fileId) {

                $preview = self::parseCSVBlob($fileId, array("limit" => 4));
                array_push($data->previews, $preview);

                if($preview->file_type == LakeConstants::CALIBRATION_FILE) {
                    $calibration_count = $calibration_count + 1 ;
                }

                if($preview->file_type == LakeConstants::DATA_FILE) {
                    $data_count = $data_count + 1 ;
                }

                if($preview->num_columns < 2 ) {
                    $xmsg = "error: file with zero or one column found!" ;
                    array_push($data->errors, $xmsg) ;
                }
                
            }

            if($calibration_count > 1 ) {
                $xmsg = "error: more than one calibration file found!" ;
                array_push($data->messages, $xmsg) ;
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

        static function parseCSVBlob($fileId, $options=array()) {

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

                // error if no parts
                /*
                if($num_parts < 2 ) {
                    $xmessage = "csv data file should contain at least 2 columns" ;
                    throw new UIException(array($xmessage), E_USER_ERROR);
                } */

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

                array_push($result->rows, $fixed_parts);
                $count = $count + 1 ;

                // guess file type
                if($count == 1 ){
                    $num_parts = sizeof($parts);

                }

                if(array_key_exists("limit", $options)) {
                    $limit = intval($options["limit"]);
                    if($count >= $limit) {
                        break ;
                    }

                }
            }

            // csv parser response 
            $result->count = $count ;
            $result->num_columns = $num_parts ;
            $result->num_date_columns = $date_col ;
            $result->numeric_columns = $numeric_col ;

            // guess file type
            if( ($date_col == 0) && ($num_parts == 2)) {
                $result->file_type = LakeConstants::CALIBRATION_FILE ;
            } else if ( ($date_col == 2) && ($num_parts == 3) ) {
                $result->file_type = LakeConstants::DATA_FILE ;
            } else {
                $result->file_type = LakeConstants::UNKNOWN_FILE;
            }

            return $result ;
           
        }
    }

}

?>