<?php

    // file download script
    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Url as Url  ;
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql as MySQL;

    
	use \com\indigloo\Logger ;
	use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\api\Response as Response ;
    use \com\indigloo\exception\UIException as UIException;

	
	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    $fileId = Url::tryQueryParam("id") ;
    if(empty($fileId)) {
        $xmsg = "required parameter id is missing" ;
        Response::raiseBadInputError($xmsg);
    }

    $mysqli = MySQL\Connection::getInstance()->getHandle();
    // get metadata 
    $fileId = $mysqli->real_escape_string($fileId);
    $sql = sprintf(" select file_name, file_size, mime from atree_file_blob where id = %d",$fileId) ;
    $row = MySQL\Helper::fetchRow($mysqli, $sql);
    
    // get BLOB 
    $stmt = $mysqli->prepare("select file_blob from atree_file_blob WHERE id= ?"); 
	$stmt->bind_param("i", $fileId);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($blob);
	$stmt->fetch();

    // @todo check headers
    
    header('Content-Description: binary file download');
    header('Content-Type: '.$row["mime"]);
    header('Content-Disposition: attachment; filename="'.$row["file_name"].'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: no-cache');
    header('Content-Length: ' .$row["file_size"]);
    
    // send blob
    echo $blob ;

    // relase resources 
    MySQL\Connection::getInstance()->closeHandle() ;
    exit; 

?>
