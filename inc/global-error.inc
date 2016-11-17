<?php

	
	function webgloo_error_handler($errno,$errstr,$file,$line) {
	
	    // do nothing for silenced errors
	    if(error_reporting() == 0 ) { return true ; }
	
	    switch($errno) {
	        case E_STRICT :
	            $message = sprintf("%s:%d [%d]  __STRICT__ %s",$file,$line,$errno,$errstr);
	            \com\indigloo\Logger::getInstance()->error($message);
	            break ;
	        case E_NOTICE :
	        case E_USER_NOTICE :
	            $message = sprintf("%s:%d [%d]  __NOTICE__ %s",$file,$line,$errno,$errstr);
	            \com\indigloo\Logger::getInstance()->error($message);
	            break ;
	
	        // E_USER_ERROR may not necessarily be a well defined error
	        // Hence E_USER_ERRORS should not bubble back to the user
	        case E_USER_ERROR:
	        default:
	            throw new ErrorException($errstr, 0, $errno, $file, $line);
	    }
	}
	
	function webgloo_exception_handler($ex) {
	   
	    if($ex instanceof \com\indigloo\exception\UIException) {
		// As of PHP 5.4 it is possible to array dereference the result 
		// of a function or method call directly. Before it was only 
		// possible using a temporary variable
		// we are playing it safe here!
		$ui_messages = $ex->getMessages();
	        $ui_message = $ui_messages[0] ;
	        $errorPageUrl = "/site/500.php?message=".$ui_message ;
	        header('Location: '.$errorPageUrl);
	        // no logging for UI messages
	        
	    } else {
	    	
	    	/* error message */
	    	$message = $ex->getMessage();
	    	$message = sprintf("%s:%d  __EXCEPTION__ %s",$ex->getFile(),$ex->getLine(),$message);
	    	\com\indigloo\Logger::getInstance()->error($message);
	    	\com\indigloo\Logger::getInstance()->backtrace($ex->getTrace());
	    	
	    	header('Location: /site/500.php');
	    }
	
	    
	    exit(1) ;
	}
	
	function offline_exception_handler($ex) {
	    $message = $ex->getMessage();
	    $message = sprintf("%s:%d  __OFFLINE__ %s \n",$ex->getFile(),$ex->getLine(),$message);
	    \com\indigloo\Logger::getInstance()->error($message);
	    \com\indigloo\Logger::getInstance()->backtrace($ex->getTrace());
	    exit(1) ;
	}
	
	function webgloo_ajax_exception_handler($ex) {
	
	    
	    if($ex instanceof \com\indigloo\exception\UIException) {
	    	// first message
		$ui_messages = $ex->getMessages();
	        $ui_message = $ui_messages[0] ;
	        
	        $error = array('code' => 500, 'error' => $ui_message);
	        $strJson =  json_encode($error) ;
	        echo $strJson ;
	        
	    } else {
	    	$message = $ex->getMessage();
	    	
	    	$message = sprintf("%s:%d  __EXCEPTION__ %s \n",$ex->getFile(),$ex->getLine(),$message);
	    	\com\indigloo\Logger::getInstance()->error($message);
	    	\com\indigloo\Logger::getInstance()->backtrace($ex->getTrace());
	    	
	    	$ui_message =  "The server encountered an error!";
	    	$error = array('code' => 500, 'error' => $ui_message);
	    	$strJson =  json_encode($error) ;
	    	echo $strJson ;
	    }
	 
	    exit(1) ;
	}
	
	// set default error handler. Without error_types mask our error_handler will be called 
	// for every error regardless of the error_reporting setting
	// mixed set_error_handler($callable, int error_types)
	
	ob_start();
	set_error_handler('webgloo_error_handler');
	set_exception_handler('webgloo_exception_handler');

?>
