<?php

namespace com\yuktix\lake\api {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    
    class Response {

        static function raiseBadInputError($xmsg) {  

            $responseObj = new \stdClass ;
            $responseObj->code = 400;
            $responseObj->error = $xmsg ;
            echo json_encode($responseObj) ;
            exit(0) ;

        }

       
    }

}


?>