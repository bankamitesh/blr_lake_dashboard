<?php
    include('lake-app.inc') ;
    include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\Logger ;
    use \com\yuktix\agent\dao\Device as DeviceDao ;

    $devices = DeviceDao::getList();
    print_r($devices);

    
?>
