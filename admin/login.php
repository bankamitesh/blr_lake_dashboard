<?php
		include ("lake-app.inc");
		include(APP_WEB_DIR.'/inc/header.inc');

		use \com\indigloo\Url ;
		use \com\yuktix\lake\auth\Login as Login ;

		// already have login?
		// do not redirect from login page.
		// we redirect to login page for missing roles as well.

		$gparams = new \stdClass ;
		$gparams->debug = false ;
		$gparams->base = Url::base() ;
	
		if(array_key_exists("jsdebug", $_REQUEST)) {
			$gparams->debug = true ;
		}

?>

		<!DOCTYPE html>
		<html ng-app="YuktixApp">
		
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
			<link rel="stylesheet" href="/assets/css/material.min.css">
			<link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_green-amber.min.css" />
			<link rel="stylesheet" href="/assets/css/main.css">
			
		</head>

		<body ng-controller="yuktix.lake.admin.login">

			 <div class="mdl-layout mdl-js-layout" id="container">

    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="docs-layout-content mdl-layout__content ">
        <div class="mdl-grid mdl-grid--no-spacing" id="content">
            <div id="content" class="mdl-cell mdl-cell--9-col mdl-cell--3-offset">
               <?php include(APP_WEB_DIR . '/inc/ui/page-error.inc'); ?>

			    <div id = "login-container" class="mdl-card mdl-shadow--2dp wide-mdl-card">
                 <h3 class="mdl-card__title">Sign In</h3>
                  
                  <div class="mdl-card__supporting-text">
                    <form name="loginForm">
						<div class="mdl-textfield mdl-js-textfield">
							<input name="login" ng-model="login.name"  class="mdl-textfield__input" type="text" id="login" required>
							<label class="mdl-textfield__label" for="login">Login...</label>
						</div><br>
						<div class="mdl-textfield mdl-js-textfield">
							<input name="password" ng-model="login.password" class="mdl-textfield__input" type="password" id="password" required>
							<label class="mdl-textfield__label" for="password">Password...</label>
						</div>
					</form> 
                  </div>

                  <div class="mdl-card__actions mdl-card--border">
                    <button ng-disabled="form1.$invalid" ng-click="do_login()" class="mdl-button mdl-js-button mdl-button--raised">
						Login
					</button>
                  </div>

                </div> <!-- login card -->
          </div> 
        </div> <!-- grid -->
    </main>
    
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-footer.inc'); ?>

</div> <!-- container -->
</body>

	<script src="/assets/js/material.min.js"></script>
	<script src="/assets/js/angular.min.js"></script>
	<script src="/assets/js/main.js"></script>
	<script type="text/javascript">

		yuktixApp.controller("yuktix.lake.admin.login",function($scope, user,$window) {

			$scope.do_login = function() {

				// 1. validate the form
				// 2. submit to data file using factory
				// 3. process response

				var errorObject = $scope.loginForm.$error;
				if($scope.validateForm(errorObject)) {
					return;
				}

				$scope.showProgress("verifying your login details");
				if ($scope.debug) {
					console.log("form values");
					console.log($scope.login);
				}

				// contact user factory
				user.login($scope.base, $scope.debug,$scope.login)
				.then( function(response) {

					var status = response.status || 500;
					var data = response.data || {};

					if ($scope.debug) {
						console.log("server response :");
						console.log(data);
					}

					if (status != 200 || data.code != 200) {
						console.log(response);
						var error = data.error || (status + ":error verifying login details");
						$scope.showError(error);
						return;
					}

					$window.location.href = "/admin/view/lake/list.php";

				},function(response) {
					$scope.processResponse(response);
				});

			};

			// data initialization
			$scope.login = {};
			$scope.errorMessage = "";

			$scope.gparams = <?php echo json_encode($gparams); ?> ;
			$scope.debug = $scope.gparams.debug ;
			$scope.base = $scope.gparams.base ;


		});
	</script>

</html>