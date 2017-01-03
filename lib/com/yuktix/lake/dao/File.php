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

        static function pokeFeatureFile($fileId) {

            // fetch data 
            // parse and get rows 
            // number of rows - 2 
            // date;time is present - NO
            // should be calibration file
            
            return LakeConstants::FEATURE_CALIBRATION_FILE ;

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

            $lines = explode(PHP_EOL, $blob);
            foreach($lines as $line) {

                $line = trim($line);
                // line empty or does not contains comma?
                if(empty($line)) {
                    continue ;
                }

                $parts = explode("," , $line) ;

                // error if no parts
                if(sizeof($parts) < 2 ) {
                    $xmessage = "csv data file should contain at least 2 columns" ;
                    throw new UIException(array($xmessage), E_USER_ERROR);
                }

                $fixed_parts = array() ;
                foreach($parts as $part) {
                    $value = $part ;
                    if(is_numeric($value)) {
                        $value = sprintf("%.2f", $part);
                        $value = floatval ($value);
                    }

                    array_push($fixed_parts, $value) ;
                }

                array_push($result->rows, $fixed_parts);
                $count = $count + 1 ;
                if(array_key_exists("limit", $options)) {
                    $limit = intval($options["limit"]);
                    if($count >= $limit) {
                        break ;
                    }

                }
            }

            $result->count = $count ;
            return $result ;
           
        }
    }

}

?>