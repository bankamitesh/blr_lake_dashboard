<?php

    // BLOB string test script
    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Url as Url  ;
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql as MySQL;

    
	use \com\indigloo\Logger ;
	use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\api\Response as Response ;
    use \com\indigloo\exception\UIException as UIException;

	$time1 = microtime() ;
	set_exception_handler('webgloo_ajax_exception_handler');
	
    $fileId = 3102 ;
   
    $mysqli = MySQL\Connection::getInstance()->getHandle();
    
    // get BLOB 
    $stmt = $mysqli->prepare("select file_blob from atree_file_blob WHERE id= ?"); 
	$stmt->bind_param("i", $fileId);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($blob);
	$stmt->fetch();

    // parse CSV BLOB 
    $lines = explode(PHP_EOL, $blob);
    foreach($lines as $line) {
        $parts = explode(",", $line) ;
        print_r($parts);
        echo "<br>" ;
    }

    // relase resources 
    MySQL\Connection::getInstance()->closeHandle() ;
    $time2 = microtime() ;
    echo "time diff=". ($time2- $time1);
    exit; 

?>