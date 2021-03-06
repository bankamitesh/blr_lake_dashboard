<?php
    // application header
    // classes should be loaded first or we get incomplete class object errors
    // Also, do not output anything in class_loader (apart from errors ) - otherwise you will
    // get session header related warnings. Please keep this order.

    ob_start() ;
    include(APP_CLASS_LOADER);
    include(APP_WEB_DIR . '/inc/global-error.inc');
    include(APP_WEB_DIR . '/inc/mysql-session.inc');

    use com\indigloo\core\Web  ;
    use com\indigloo\Configuration as Config  ;

    $gWeb = Web::getInstance();
    $gWeb->start();

    //define constants
    define("G_APP_NAME", "lakeapp");
    
?>
