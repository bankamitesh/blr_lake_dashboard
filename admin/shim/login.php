<?php 

    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
	use \com\indigloo\Configuration as Config;
	use \com\indigloo\Logger ;
	use \com\yuktix\auth\Login as Login ;
    use \com\indigloo\exception\UIException as UIException;
	use \com\indigloo\util\StringUtil as StringUtil ;
	
	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    // PHP $_REQUEST only works for x-www-form-urlencoded content type
    // so we have to get the raw data when content-type is application/json 
    // 
    $logins = array ("manju" => "manju1234" , "rjha94" => "rjha94") ;
    	
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;
    $name = $postData->name ;
    $password = $postData->password ;

    $xmsg = sprintf("from request: name:%s, password:%s",$name,$password);
    Logger::getInstance()->info($xmsg);


    $responseObj = new \stdClass ;

    if(array_key_exists($name, $logins)) {
        $dbPassword = $logins[$name] ;

        if(strcmp($dbPassword, $password) == 0 ) {
            // success
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
