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
		<link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.min.css" />
		<link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.min.css" />
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link rel="stylesheet" href="/test/bootstrap/assets/css/style.css" />
	</head>
		
	<body ng-controller="yuktix.lake.admin.login">
		<div>
			<div>
				<?php include(APP_WEB_DIR . '/inc/ui/bootstrap-header.inc'); ?>	
			</div>
			<main>
				<?php include(APP_WEB_DIR . '/inc/ui/bootstrap-progress.inc'); ?>
				<div class="container">
					<div class="row">
						<div  class="col-md-2"></div>
						<div  class="col-md-8" >
							<?php include(APP_WEB_DIR . '/inc/ui/bootstrap-page-message.inc'); ?>
							<div class="login-style">	
								<form name="loginForm">
									<div class="form-group">
										<h3>Sign In</h3>
									</div>
									<div class="form-group">
										<img class="img-responsive" src="/assets/images/lake_top_3.jpg" >
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Username</label>
										<input name="login" ng-model="login.name" type="text" class="form-control" id="login" required>
									</div>
									<div class="form-group">
										<label for="exampleInputPassword1">Password</label>
										<input name="password" ng-model="login.password" type="password" class="form-control" id="password" required>
									</div>
									<div class="form-group">
										<button ng-disabled="form1.$invalid" ng-click="do_login()" class="btn btn-default">
											Submit
										</button>
									</div>
								</form>
							</div>
						</div>
						<div  class="col-md-2"></div>
					</div>	
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php include(APP_WEB_DIR . '/inc/ui/bootstrap-footer.inc'); ?>
					</div>
				</div> 
			</main>
		</div>
	</body>			
	
	<script src="/assets/js/jquery-2.1.1.min.js"></script>
	<script src="/test/bootstrap/assets/js/bootstrap.min.js"></script>
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

					$window.location.href = "/admin/lake/list.php";

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