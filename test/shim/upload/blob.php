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
	
    // PHP $_REQUEST only works for x-www-form-urlencoded content type
    // so we have to get the raw data when content-type is application/json 
    $rawPostData = file_get_contents("php://input");
    
    // save to a file
    $fh = fopen("/home/manju/code/blr_lake_dashboard/test/blobdata/test.png", "w");
    fwrite($fh, $rawPostData);
    fclose($fh);

    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->response = "file upload is success!" ;
    echo json_encode($responseObj) ;
    exit(0) ;


?>
