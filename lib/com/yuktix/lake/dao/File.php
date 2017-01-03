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

    }

}

?>