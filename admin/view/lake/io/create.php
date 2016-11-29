<?php

include("lake-app.inc");
include(APP_WEB_DIR . '/inc/header.inc');

use \com\indigloo\Url;

$gparams = new \stdClass;
$gparams->debug = false;
$gparams->base = Url::base();

if (array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true;
}

?>

<!DOCTYPE html>
<html ng-app="YuktixApp">
<head>
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/assets/css/mdl-selectfield.min.css">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body ng-controller="yuktix.admin.lake.io.create">
<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <div class="mdl-layout-spacer"></div>

    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <?php include(APP_WEB_DIR . '/inc/title.inc'); ?>
        </div>
    </header>

    <main class="mdl-layout__content">
        <div class="page-content">
            <div class=""></div>
            <?php include(APP_WEB_DIR . '/inc/page_error.inc'); ?>
            <!-- card -->
            <div class="mdl-grid pad-bottom">
                <div class="mdl-layout-spacer"></div>
                <div class="mdl-cell mdl-cell--6-col mdl-shadow--4dp">
                    <div class="mdl-card__title formcard mdl-color-text--white">
                        <h2 class="mdl-card__title-text formcard mdl-color-text--indigo">Create Inlet</h2>
                    </div>
                    <div class="pad-left-form-field">
                        <form name="IoCreateForm">

                            <div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label">
                                <select id="profile_information_form_dob_2i" name="profile_information_form[dob(2i)]"
                                        class="date required mdl-selectfield__select" required>
                                    <option value=""></option>
                                    <option value="IN">Storm Water Inlet</option>
                                    <option value="CN">Sewage Inlet</option>
                                    <option value="JP">Mixed Inlet</option>
                                    <option value="JP">Outlet</option>
                                </select>
                                <label for="profile_information_form_dob_2i"
                                       class="mdl-selectfield__label">Type...</label>
                                <span class="mdl-selectfield__error">Input is not a empty!</span>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="name">
                                <label class="mdl-textfield__label" for="sample3">Width...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="name">
                                <label class="mdl-textfield__label" for="sample3">Height...</label>
                            </div>
                            <br>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="name">
                                <label class="mdl-textfield__label" for="sample3">Lattitude...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="name">
                                <label class="mdl-textfield__label" for="sample3">Longtitude...</label>
                            </div>
                            <br>

                            <!--need to put upload component-->
                            <h5>Photos </h5>
                            <br>


                            <div class="pad-top-form-field"></div>
                            <label class="mdl-radio mdl-js-radio" for="option1">
                                <input type="radio" id="option1" name="select" class="mdl-radio__button"
                                       ng-click="showData('sensor')">
                                <span class="mdl-radio__label">Sensor Installed</span>
                            </label><br>

                            <label class="mdl-radio mdl-js-radio" for="option2">
                                <input type="radio" id="option2" name="select" class="mdl-radio__button"
                                       ng-click="showData('level')">
                                <span class="mdl-radio__label">Lake level Related</span>
                            </label><br>

                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option3">
                                <input type="radio" id="option3" name="select" class="mdl-radio__button"
                                       ng-click="showData('const')">
                                <span class="mdl-radio__label">Constant Value</span>
                            </label><br>


                            <div class="mdl-textfield mdl-js-textfield">
                                <textarea class="mdl-textfield__input" type="text" rows="3" id="about"></textarea>
                                <label class="mdl-textfield__label" for="text7">Sensor Details...</label>
                            </div>
                            <br>

                            <h5>Select Install Date</h5>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="custom-date-box">
                                <label class="mdl-textfield__label" for="sample3">Date...</label>
                            </div>
                            <br>


                            <h5>Meta Data</h5>
                            <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--5-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="name">
                                        <label class="mdl-textfield__label" for="sample3">Name...</label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--5-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="name">
                                        <label class="mdl-textfield__label" for="sample3">Value...</label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--2-col mdl-cell--middle">
                                    <button
                                        class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored">
                                        <i class="material-icons">add</i></button>
                                </div>
                            </div>


                            <div ng-show="IsSensor">
                                <h5>SensorStage-Flow CSV</h5>

                            </div>
                            <br>

                            <div ng-show="IsLevel">
                                <h5>LakeStage-Flow CSV</h5>

                            </div>
                            <br>
                            
                            <div ng-show="IsConst">
                                <h5>Constant</h5>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="name">
                                    <label class="mdl-textfield__label" for="sample3">FlowRate...</label>
                                </div>
                            </div>


                            <div class="pad-top-form-field"></div>


                            <!-- </form> -->
                    </div>
                    <div class="mdl-card__actions mdl-card--border">
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-color-text--indigo" ng-click="create_inlet()" type="submit">Save</button>
                    </div>
                    </form>
                </div>
                <div class="mdl-layout-spacer"></div>
            </div>
            <!-- end card -->


        </div>
        <?php include(APP_WEB_DIR . '/inc/footer.inc'); ?>
    </main>
</div>
<script src="/assets/js/material.min.js"></script>
<script src="/assets/js/mdl-selectfield.min.js"></script>
<script src="/assets/js/angular.min.js"></script>
<script src="/assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    yuktixApp.controller("yuktix.admin.lake.io.create", function ($scope, io, $window) {


        $scope.create_inlet = function () {


            var errorObject = $scope.createForm.$error;
            if ($scope.validateForm(errorObject)) {
                return;
            }

            $scope.showProgress("verifying your login details");
            if ($scope.debug) {
                console.log("form values");
                console.log($scope.create);
            }

            // contact user factory
            io.inletCreate($scope.base, $scope.debug, $scope.createObj)
                .then(function (response) {

                    var status = response.status || 500;
                    var data = response.data || {};

                    if ($scope.debug) {
                        console.log("server response :");
                        console.log(data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log(response);
                        var error = data.error || (status + ":error while submitiing data ");
                        $scope.showError(error);
                        return;
                    }

                    $window.location.href = "/admin/view/lake/list.php";

                }, function (response) {
                    $scope.processResponse(response);
                });


        };

            $scope.IsSensor = false;
            $scope.IsLevel = false;
            $scope.IsConst = false;

        $scope.showData = function (value) {
            //If DIV is visible it will be hidden and vice versa.
            if ($scope.IsSensor = value == "sensor") {
                $scope.IsSensor = true;
                $scope.IsLevel = false;
                $scope.IsConst = false;
            }
            else if ($scope.IsLevel = value == "level") {
                $scope.IsLevel = true;
                $scope.IsSensor = false;
                $scope.IsConst = false;
            }
            else if ($scope.IsConst = value == "const") {
                $scope.IsConst = true;
                $scope.IsSensor = false;
                $scope.IsLevel = false;
            }
            else {

            }

        };


        // data initialization
        $scope.createObj = {};
        $scope.errorMessage = "";

        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug;
        $scope.base = $scope.gparams.base;


    });

    $( function() {
        $("#custom-date-box").datepicker({ dateFormat: 'dd-mm-yy' });
    } );

</script>
</body>
</html>