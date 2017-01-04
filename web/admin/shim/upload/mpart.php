<?php 

    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql\PDOWrapper;
    use \com\indigloo\exception\DBException;

	use \com\indigloo\Logger ;
	use \com\indigloo\Util as Util ;
	use \com\indigloo\util\StringUtil as StringUtil ;

	use \com\yuktix\lake\auth\Login as Login ;
    use \com\indigloo\exception\UIException as UIException;

	function map_php_file_error($error) {

		$messages = array(
			UPLOAD_ERR_INI_SIZE => "file size is greater than limit set in php.ini",
			UPLOAD_ERR_PARTIAL => "file upload is partial",
			UPLOAD_ERR_NO_FILE => "no file selected for upload",
			UPLOAD_ERR_FORM_SIZE => "file exceeds MAX_FILE_SIZE limit on HTML form",
			UPLOAD_ERR_NO_TMP_DIR => "no temporary directory found",
			UPLOAD_ERR_CANT_WRITE => "failed to write file to disk",
			UPLOAD_ERR_EXTENSION => "php extension caused issue with file upload"

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

	function save_to_database($fname, $tmp_file, $mime,$loginId, $email) {

		// save to database 
		// $fname, code, size, blob
		$fp_tmp_file = fopen($tmp_file, "rb");
		$size = filesize($tmp_file);

		if($size == 0) {
			trigger_error("file size is zero!", E_USER_ERROR);
		}

		$dbh = NULL ;
		$lastInsertId = NULL ;

    	try {

			$dbh = PDOWrapper::getHandle();
			$sql = "insert INTO atree_file_blob(file_name, file_size, file_blob, "
					. " login_id, email, mime, created_on) "
					. " VALUES (:name, :size, :blob, :login_id, :email, :mime, now()) ";
					

			// Tx start
			$dbh->beginTransaction();
			$stmt = $dbh->prepare($sql);
			
			// bind params 
			$stmt->bindParam(":name",$fname, \PDO::PARAM_STR);
			$stmt->bindParam(":size",$size, \PDO::PARAM_INT);
			$stmt->bindParam(":blob",$fp_tmp_file, \PDO::PARAM_LOB);
			$stmt->bindParam(":login_id",$loginId, \PDO::PARAM_INT);
			$stmt->bindParam(":email",$email, \PDO::PARAM_STR);
			$stmt->bindParam(":mime",$mime, \PDO::PARAM_STR);

			$stmt->execute();
			$stmt = NULL;
			$lastInsertId = $dbh->lastInsertId() ;

			// Tx end
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

	// main script start
	// login check
	Login::isCustomerAdmin();
	$login = Login::getLoginInSession();

	$metadata = NULL ;
	$metadataObj = new \stdClass ;
	$fname = NULL ;

	// store is [disk | database ]
	$store = "disk" ;
	$lastInsertId = NULL ;

	// input check
	if(!isset($_POST["metadata"])) {
		quit_with_error(400, "error: missing metadata in POST");
	}

	// @todo add code back?
	$metadata = $_POST["metadata"];
	$metadataObj = json_decode($metadata) ;

	if(property_exists($metadataObj, "store")) {
		$store = $metadataObj->store ;
	}

	// input check: done
	$xmsg = sprintf("upload/mpart.php : metadata:%s",$metadata); 
	Logger::getInstance()->info($xmsg);

	foreach($_FILES as $index => $file) {
		
		if(Config::getInstance()->is_debug()) {
			$xmsg = sprintf("/admin/shim/upload/mpart.php: index:%d, file=%s >>",$index, $file["name"]);
			Logger::getInstance()->debug($xmsg);
		}

		$fname =  basename($file["name"]);
		$tmp_file = $file["tmp_name"];
		
		/*
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp_file);
        $mime = ($mime === FALSE ) ?  "application/octet-stream" : $mime ; 

		Logger::getInstance()->info("upload/mpart.php, mime=".$mime); 
		
		*/

		$mime = "application/octet-stream" ; 
		if(!empty($file["error"])) {
			$xmsg = map_php_file_error($file["error"]);
			quit_with_error(500,$xmsg);
		}

		if(strcmp($store,"disk") == 0 ) {
			// special case: only for testing
			// save to disk for debugging
			save_to_disk($fname, $tmp_file) ;
		} else { 
			$lastInsertId = save_to_database($fname, $tmp_file, $mime,$login->id, $login->email) ;
		}

	}

	$responseObj = new \stdClass ;
	$responseObj->code = 200;
	$responseObj->response = "file upload is success!" ;
	$responseObj->fileId = $lastInsertId ;
	$responseObj->name = $fname ;

	echo json_encode($responseObj) ;
	exit(0) ;


?>
