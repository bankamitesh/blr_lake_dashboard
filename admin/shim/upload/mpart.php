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

	function map_php_file_error($error) {

		$messages = array(
			UPLOAD_ERR_INI_SIZE => "file size is greater than limit set in php.ini",
			UPLOAD_ERR_PARTIAL => "file upload is partial",
			UPLOAD_ERR_NO_FILE => "no file selected for upload"

		);

		$message = "unknown error" ;
		if(array_key_exists($error,$messages)) {
			$message = $messages[$error];
		}

		$xmsg = sprintf("php upload: error: %d, message: %s", $error, $message);
		return $xmsg ;

	}

	function quit_with_error($code, $error) {

		$responseObj = new \stdClass ;
		$responseObj->code = $code;
		$responseObj->error = $error ;
		echo json_encode($responseObj) ;
		exit(1) ;

	}

	function save_to_disk($fname, $tmp_file) {

		if(!empty($tmp_file) && is_uploaded_file($tmp_file)) {
			$path = Config::getInstance()->get_value('system.upload.path')."/".$fname;
			move_uploaded_file($tmp_file, $path);
		} else {
			$xmsg = "error :file missing or did not receive via POST";
			quit_with_error(500, $xmsg) ;
		}

	}

	function save_to_database($fname, $tmp_file, $code) {

		// save to database 
		// $fname, code, size, blob
		$fp_tmp_file = fopen($tmp_file, "rb");
		$size = filesize($tmp_file);
		$createdBy = "test" ;

		$dbh = NULL ;
		$lastInsertId = NULL ;

    	try {

			$dbh = PDOWrapper::getHandle();
			$sql = "insert INTO atree_file_blob(file_name, file_size, file_code, file_blob, "
					. " created_by, created_on) "
					. " VALUES (:name, :size, :code, :blob, :created_by, now()) ";
					

			// Tx start
			$dbh->beginTransaction();
			$stmt = $dbh->prepare($sql);
			
			// bind params 
			$stmt->bindParam(":name",$fname, \PDO::PARAM_STR);
			$stmt->bindParam(":size",$size, \PDO::PARAM_INT);
			$stmt->bindParam(":code",$code, \PDO::PARAM_STR);
			$stmt->bindParam(":blob",$fp_tmp_file, \PDO::PARAM_LOB);
			$stmt->bindParam(":created_by",$createdBy, \PDO::PARAM_STR);

			$stmt->execute();
			$stmt = NULL;
			$lastInsertId = $dbh->lastInsertId() ;

			//Tx end
			$dbh->commit();
			$dbh = null;

		} catch (\Exception $ex) {

			$dbh->rollBack();
			$dbh = null;
			throw $ex ;
		}

		return $lastInsertId ;
	}

	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();

	$metadata = NULL ;
	$metadataObj = new \stdClass ;

	// store is [disk | database ]
	$store = "disk" ;
	$code = NULL ;
	$lastInsertId = NULL ;

	// input check
	if(!isset($_POST["metadata"])) {
		quit_with_error(400, "error: missing metadata in POST");
	}

	$metadata = $_POST["metadata"];
	$metadataObj = json_decode($metadata) ;

	if(!property_exists($metadataObj, "code")) {
		quit_with_error(400, "error: missing file code in POST metadata");
	}

	$code = $metadataObj->code ;
	if(empty($code)) {
		quit_with_error(400, "error: bad file code in POST metadata");
	}

	if(property_exists($metadataObj, "store")) {
		$store = $metadataObj->store ;
	}

	// input check: done
	$xmsg = sprintf("upload/mpart.php : metadata:%s",$metadata); 
	Logger::getInstance()->info($xmsg);

	foreach($_FILES as $index => $file) {
		
		$xmsg = sprintf("upload/mpart.php: index:%d, file=%s",$index, $file["name"]);
		Logger::getInstance()->info($xmsg);
	
		$fname =  basename($file["name"]);
		$tmp_file = $file["tmp_name"];
		
		if(!empty($file["error"])){
			$xmsg = map_php_file_error($file["error"]);
			quit_with_error(500,$xmsg);
		}

		if(strcmp($store,"disk") == 0 ) {
			// special case
			// save to disk for debugging
			save_to_disk($fname, $tmp_file) ;
		} else { 
			$lastInsertId = save_to_database($fname, $tmp_file, $code) ;
		}

	}


	$responseObj = new \stdClass ;
	$responseObj->code = 200;
	$responseObj->response = "file upload is success!" ;
	$responseObj->fileId = $lastInsertId ;

	echo json_encode($responseObj) ;
	exit(0) ;


?>
