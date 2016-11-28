<?php

namespace com\yuktix\lake\auth {

    use \com\indigloo\Url as Url;
    use \com\indigloo\Util as Util;
    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    
    use \com\indigloo\exception\UIException as UIException;

    class Login {

        const LOGIN_ID = "login-id-in-session";
        const SESSION_KEY = "session-key-in-session" ;
        
        const USER_FIRST_NAME = "user-first-name" ;
        const USER_LAST_NAME = "user-last-name" ;
        const USER_EMAIL = "user-email";
        const ACCOUNT_NAME = "account-name" ;
        const ROLES_ARRAY   = "roles-array";
        	
        //codes
        const OK_CODE = 200 ;
        const FORBIDDEN_CODE = 403 ;
        
        static function startOAuth2Session($provider,$user) {
        	
        	if(empty($provider) || empty($user)) {
        		$message = "session : provider or user is missing.";
        		throw new UIException(array($message));
        	}
        	
        	if(empty($user->sessionKey)) {
        		$message = "session: user session key is missing.";
        		throw new UIException(array($message));
        	}
        	
            $_SESSION[self::LOGIN_ID] = $user->loginId;
            $_SESSION[self::SESSION_KEY] = $user->sessionKey;
            
            $_SESSION[self::USER_FIRST_NAME] = $user->firstName;
            $_SESSION[self::USER_LAST_NAME] = $user->lastName;
            $_SESSION[self::USER_EMAIL] = $user->email;
            $_SESSION[self::ACCOUNT_NAME] = $user->accountName;
            
            // start with user
            $_SESSION[self::ROLES_ARRAY] = array(1); 
            return self::OK_CODE ;

        }
        
        static function setRoles($roles) {
        	$_SESSION[self::ROLES_ARRAY] = $roles;
        }
        
        static function getLoginInSession() {

            if (isset($_SESSION) && isset($_SESSION[self::SESSION_KEY])) {
                $login = self::createLoginObject();
                return $login ;

            } else {
                $message = "User session does not exists!" ;
                throw new UIException(array($message));
            }

        }
        
        static function tryLoginInSession() {

            if (isset($_SESSION) && isset($_SESSION[self::SESSION_KEY])) {
                $login = self::createLoginObject();
                return $login;
                
            } else {
                return NULL;
            }

        }
        
        static function tryLoginIdInSession() {
            $loginId = NULL ;

            if (isset($_SESSION) && isset($_SESSION[self::LOGIN_ID])) {
                $loginId = $_SESSION[self::LOGIN_ID] ;
            }
            return $loginId ;
        }

        static function getLoginIdInSession() {
        	
            $loginId = NULL ;
            
            if (isset($_SESSION) && isset($_SESSION[self::LOGIN_ID]) ) {
                $loginId = $_SESSION[self::LOGIN_ID] ;
            } else{
                $message = "User session does not exists!" ;
                throw new UIException(array($message));
            }

            return $loginId ;

        }

        static function hasSession(){
            
            $loginId = self::tryLoginIdInSession();
            $flag = is_null($loginId) ? false : true ;
            return $flag ;
        }
        
        static function isUser($loginPage="/admin/login.php") {
        	
        	if(!self::hasSession()) {
        		self::gotoLogin($loginPage);
        	}
        	
        	return ;
        }
        
        static function isSuperAdmin($loginPage="/admin/login.php") {
        	if(!self::hasSuperAdminRole()) {
        		self::gotoLogin($loginPage) ;
        	}
        	
        	return  ;
        }
        
        static function isCustomerAdmin($loginPage="/admin/login.php") {
        	if(! (self::hasSuperAdminRole() || self::hasCustomerAdminRole())) {
        		self::gotoLogin($loginPage) ;
        	}
        	
        	return  ;
        }
        
        // ***********************************************************
        
        private static function createLoginObject() {
        	 
        	$login = new \stdClass ;
        	 
        	$login->id = $_SESSION[self::LOGIN_ID] ;
        	$login->sessionKey = $_SESSION[self::SESSION_KEY];
        	 
        	$login->firstName = $_SESSION[self::USER_FIRST_NAME];
        	$login->lastName = $_SESSION[self::USER_LAST_NAME];
        	$login->email = $_SESSION[self::USER_EMAIL];
        	$login->accountName = $_SESSION[self::ACCOUNT_NAME];
        	 
        	$login->roles = $_SESSION[self::ROLES_ARRAY];
        	$login->superAdmin = self::hasSuperAdminRole();
        	$login->customerAdmin = self::hasCustomerAdminRole();
        	
        	return $login;
        }
        
        private static function hasSuperAdminRole() {
        	$roles = array() ;
        	if (isset($_SESSION) && isset($_SESSION[self::ROLES_ARRAY])) {
        		$roles = $_SESSION[self::ROLES_ARRAY] ;
        	}
        
        	$flag = (in_array(3,$roles)) ? true : false ;
        	return $flag ;
        }
        
        private static function hasCustomerAdminRole() {
        	$roles = array() ;
        	if (isset($_SESSION) && isset($_SESSION[self::ROLES_ARRAY])) {
        		$roles = $_SESSION[self::ROLES_ARRAY] ;
        	}
        
        	$flag = (in_array(2,$roles)) ? true : false ;
        	return $flag ;
        }
        
        private static function gotoLogin($loginPage) {
        	$requestURI = $_SERVER['REQUEST_URI'];
        	$fwd = empty($requestURI) ? $loginPage : $loginPage."?redirect_to=".$requestURI ;
        	header('location: '.$fwd);
        	exit ;
        }
        
    }
}
?>