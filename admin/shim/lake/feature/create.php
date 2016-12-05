<?php 

    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql\PDOWrapper;
	use \com\indigloo\Logger ;
	
	use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\mysql\Feature as Feature ;

    use \com\indigloo\exception\UIException as UIException;

    // start:script
	set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    $rawPostData = file_get_contents("php://input");
    $postData = json_decode($rawPostData) ;

    $dbh = NULL ;
    $featureId = NULL ;

    try {

        $dbh = PDOWrapper::getHandle();
        $dbh->beginTransaction();
        $featureId = Feature::insert($dbh, $postData) ;
        $dbh->commit();
        $stmt = NULL;
        $dbh = null;

    } catch (\Exception $ex) {
        $dbh->rollBack();
        $dbh = null;
        throw $ex ;
    }

    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->response = "lake feature creation is success!" ;
    $responseObj->featureId = $featureId ;
    echo json_encode($responseObj) ;
    exit(0) ;


?>
