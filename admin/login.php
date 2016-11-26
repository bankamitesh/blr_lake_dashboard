		<?php

			include ("lake-app.inc");
			include(APP_WEB_DIR.'/inc/header.inc');

			use \com\indigloo\Url ;

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
			<link rel="stylesheet" href="/assets/css/material.min.css">
			<link rel="stylesheet" href="/assets/css/main.css">
			<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		</head>
		<body ng-controller="yuktix.lake.admin.login">

			<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

				<header class="mdl-layout__header">
					<div class="mdl-layout__header-row">
						<?php include (APP_WEB_DIR.'/inc/title.inc'); ?>
					</div>
				</header>

			  <?php /*include (APP_WEB_DIR.'/inc/toolbar.inc'); */?>
			  <div class="mdl-layout-spacer"></div>

				<main class="mdl-layout__content">
					<div class="page-content">
					<div class="pad-bottom"></div>
							<?php include (APP_WEB_DIR.'/inc/page_error.inc'); ?>
						<!-- card -->
						<div class="mdl-grid">
							<div class="mdl-layout-spacer"></div>
							<div class="mdl-cell mdl-cell--6-col mdl-shadow--4dp">
								<div class="mdl-card__title formcard mdl-color-text--white">
									<h2 class="mdl-card__title-text formcard mdl-color-text--indigo">Login</h2>
								</div>
								<div class="mdl-card__supporting-text mdl-color--white">
									<form name="loginForm">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input name="login" ng-model="login.name"  class="mdl-textfield__input" type="text" id="login" required>
											<label class="mdl-textfield__label" for="login">User Name...</label>
										</div><br>
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input name="password" ng-model="login.password" class="mdl-textfield__input" type="password" id="password" required>
											<label class="mdl-textfield__label" for="password">Password...</label>
										</div>
										<!-- </form> -->
									</div>
									<div class="mdl-card__actions mdl-card--border">
										<button ng-disabled="form1.$invalid" ng-click="do_login()" class="mdl-button mdl-js-button mdl-button--raised mdl-color-text--indigo">Login</button>
									</div>
								</form>
							</div>
							<div class="mdl-layout-spacer"></div>
						</div>
						<!-- end card -->


					</div>
					<?php include (APP_WEB_DIR.'/inc/footer.inc'); ?>
				</main>
			</div>
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
		</body>
		</html>