<?php 

    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
	use \com\indigloo\Configuration as Config;
	use \com\indigloo\Logger ;
	
    use \com\indigloo\exception\UIException as UIException;
	use \com\indigloo\util\StringUtil as StringUtil ;
	use \com\yuktix\lake\auth\Login as Login ;

	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
   

    $user1 = new \stdClass ;
    $user1->loginId = 1 ;
    $user1->sessionKey = "session1";
    $user1->firstName = "Manju" ;
    $user1->lastName = "Walikar" ;
    $user1->email = "mwalikar@yuktix.com" ;
    $user1->accountName = "Yuktix Technologies";
    $user1->password =  "mwalikar" ;

    $user2 = new \stdClass ;
    $user2->loginId = 2 ;
    $user2->sessionKey = "session2";
    $user2->firstName = "Rajeev" ;
    $user2->lastName = "Jha" ;
    $user2->email = "rjha@yuktix.com" ;
    $user2->accountName = "Yuktix Technologies";
    $user2->password = "rjha94" ;

    // PHP $_REQUEST only works for x-www-form-urlencoded content type
    // so we have to get the raw data when content-type is application/json 
    
    $logins = array ("manju" => $user1 , "rjha94" => $user2) ;
    	
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;
    $name = $postData->name ;
    $password = $postData->password ;

    $xmsg = sprintf("from request: name:%s, password:%s",$name,$password);
    Logger::getInstance()->info($xmsg);


    $responseObj = new \stdClass ;

    if(array_key_exists($name, $logins)) {
        $user = $logins[$name] ;

        if(strcmp($user->password, $password) == 0 ) {
            // login success: start session
            $provider = "yuktix" ;
            Login::startOAuth2Session($provider,$user);
            Login::setRoles(array(1,2));

            // return response 
            $responseObj->code = 200;
            $responseObj->response = "login is success!" ;
            echo json_encode($responseObj) ;
            exit(0) ;
        }

    }

    $responseObj->code = 400;
    $responseObj->error = "error: login or password is incorrect" ;
    echo json_encode($responseObj) ;
    exit(0) ;
    
?>
