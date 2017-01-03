<?php

namespace com\yuktix\lake\dao {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\indigloo\mysql as MySQL;
    use \com\indigloo\mysql\PDOWrapper;
    use \com\indigloo\exception\UIException as UIException;

    use \com\yuktix\lake\api\Response as Response ;
    use \com\yuktix\lake\mysql\Lake as LakeDB ;
    use \com\yuktix\lake\dao\File as FileDao ;
    use \com\yuktix\lake\Constants as LakeConstants ;
    
    class Feature {

        static function uploadData($lakeId, $featureObj, $fileIds) {

            $calibration_count = 0 ;
            $data_count = 0 ;

            foreach($fileIds as $fileId) {

                // peek into file and get type
                $file_type = FileDao::pokeFeatureFile($fileId) ;
                if($file_type == LakeConstants::FEATURE_CALIBRATION_FILE ) {
                    $calibration_count = $calibration_count + 1 ;
                    if($calibration_count > 1 ) { 
                        throw new UIException(array("only one calibration file can be uploaded!"));
                    }

                } else {
                    $data_count = $data_count + 1 ;
                }

            }

            if($data_count == 0 ) {
                throw new UIException(array("no data file found in upload!"));
            }
            
            // peek into data 
            // figure out calibration file and level file
            // get user confirmation 
            // more than 1 calibration file: error
            // no level data file: error

        }
    }

}

?>
