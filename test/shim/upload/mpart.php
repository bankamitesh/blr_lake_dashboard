<?php 

    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql\PDOWrapper;
    use \com\indigloo\exception\DBException;

	use \com\indigloo\Logger ;
	use \com\indigloo\util\StringUtil as StringUtil ;

	use \com\yuktix\auth\Login as Login ;
    use \com\indigloo\exception\UIException as UIException;

	
	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    Logger::getInstance()->info("inside mpart.php script...");
    Logger::getInstance()->info($_POST["metadata"]);
    
	foreach($_FILES as $index => $file) {
		Logger::getInstance()->info("inside mpart.php / file iteration...");
		$fname     =  basename($file["name"]);
		$tmpFile = $file["tmp_name"];
		
		if(!empty($file["error"])){
			$responseObj = new \stdClass ;
            $responseObj->code = 200;
            $responseObj->error = $file["error"] ;
            echo json_encode($responseObj) ;
            exit(1) ;

		}

		if(!empty($tmpFile) && is_uploaded_file($tmpFile)) {
			move_uploaded_file($tmpFile, "/Users/rjha/Documents/uploads/" . $fname);
		}
	}

    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->response = "file upload is success!" ;
    echo json_encode($responseObj) ;
    exit(0) ;


?>
