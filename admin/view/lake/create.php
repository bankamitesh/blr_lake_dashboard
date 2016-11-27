<?php

include("lake-app.inc");
include(APP_WEB_DIR . '/inc/header.inc');

use \com\indigloo\Url;
use \com\yuktix\lake\auth\Login as Login ;

// role check
// redirect to login page 
Login::isCustomerAdmin("/admin/login.php") ;

$gparams = new \stdClass;
$gparams->debug = false;
$gparams->base = Url::base();

if (array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true;
}

?>
<html ng-app="YuktixApp">
<head>
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/mdl-selectfield.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body ng-controller="yuktix.admin.lake.create">
<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <?php include (APP_WEB_DIR.'/inc/title.inc'); ?>
        </div>
    </header>

    <main class="mdl-layout__content">

        <div class="page-content">
            <div class="pad-bottom"></div>
            <?php include(APP_WEB_DIR . '/inc/page_error.inc'); ?>

            <!-- card -->
            <div class="mdl-grid">

                <div class="mdl-layout-spacer"></div>

                <div class="mdl-cell mdl-cell--6-col mdl-shadow--4dp">

                    <div class="mdl-card__title formcard mdl-color-text--indigo">
                        <h2 class="mdl-card__title-text formcard">Create Lake</h2>
                    </div>

                    <div class="pad-left-form-field">

                        <form name="createForm">
                            <div class="pad-top-form-field"></div>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" name="name" id="name"
                                       ng-model="lakeObj.name" required>
                                <label class="mdl-textfield__label" for="sample3">Lake Name </label>
                            </div>
                            <br>

                            <div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label">
                                <select id="profile_information_form_dob_2i" name="profile_information_form[dob(2i)]"
                                        class="date required mdl-selectfield__select"
                                        ng-model="selectedLakeType"
                                        ng-change="select_lake_type(selectedLakeType)"
                                        ng-options="lakeType.name for lakeType in allLakeTypes"
                                        required>
                                </select>
                                <label for="profile_information_form_dob_2i"
                                       class="mdl-selectfield__label">Type...</label>
                                <span class="mdl-selectfield__error">Input is not a empty!</span>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield">
                                <textarea class="mdl-textfield__input" type="text" rows="3" id="about" name="about"
                                          ng-model="lakeObj.about" required></textarea>
                                <label class="mdl-textfield__label" for="text7">About...</label>
                            </div>
                            <br>


                            <div class="pad-top-form-field">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="lat" name="lattitude"
                                           ng-model="lakeObj.lat" required>
                                    <label class="mdl-textfield__label" for="sample3">Lattitude...</label>
                                </div>
                            </div>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="long" name="longtitude"
                                       ng-model="lakeObj.lon" required>
                                <label class="mdl-textfield__label" for="sample3">Longtitude...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield">
                                <textarea class="mdl-textfield__input" type="text" rows="3" id="address" name="address"
                                          ng-model="lakeObj.address" required></textarea>
                                <label class="mdl-textfield__label" for="text7">Address...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="area" name="maxArea"
                                       ng-model="lakeObj.maxArea" required>
                                <label class="mdl-textfield__label" for="sample3">Max Area...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="long" name="maxVolume"
                                       ng-model="lakeObj.maxVolume" required>
                                <label class="mdl-textfield__label" for="sample3">Max Volume...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="long" name="rechargeRate"
                                       ng-model="lakeObj.rechargeRate" required>
                                <label class="mdl-textfield__label" for="sample3">Rechange Rate...</label>
                            </div>


                            <h5>Usage</h5>
                            <div class="mdl-grid mdl-grid--no-spacing">

                                <div class="mdl-cell mdl-cell--3-col" ng-repeat="usage in allLakeUsages">
                                    <label class="mdl-checkbox mdl-js-checkbox" for="{{usage.id}}">
                                        <input
                                            type="checkbox"
                                            id="{{usage.id}}" class="mdl-checkbox__input"
                                            ng-checked="lakeObj.usageCode.indexOf(usage.id) > -1"
                                            ng-click="toggle_usage_code(usage.id)"
                                            value="{usage.value}"
                                            name="{usage.name}" required>

                                        <span class="mdl-checkbox__label" ng-bind="usage.value"></span>
                                    </label>
                                </div>

                            </div>


                            <div class="pad-top-form-field">
                                <div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label">
                                    <select id="profile_information_form_dob_2i" name="profile_information_form[dob(2i)]"
                                            class="date required mdl-selectfield__select"
                                            ng-model="selectedAgency"
                                            ng-change="select_agency(selectedAgency)"
                                            ng-options="agency.name for agency in allLakeAgencies"
                                            required>
                                    </select>
                                    <label for="profile_information_form_dob_2i"
                                           class="mdl-selectfield__label">Management Agency...</label>
                                    <span class="mdl-selectfield__error">Input is not a empty!</span>
                                </div>
                            </div><br><br>

                        </form>
                    </div>
                    <div class="mdl-card__actions mdl-card--border">
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-color-text--indigo"
                                ng-click="create_lake()" type="submit">Save
                        </button>
                    </div>
                </div>
                <div class="mdl-layout-spacer"></div>
            </div>
            <!-- end card -->
            <div class="pad-bottom"></div>

        </div>
        <?php include(APP_WEB_DIR . '/inc/footer.inc'); ?>
    </main>
</div>
<script src="/assets/js/material.min.js"></script>
<script src="/assets/js/mdl-selectfield.min.js"></script>
<script src="/assets/js/angular.min.js"></script>
<script src="/assets/js/main.js"></script>

<script>

    yuktixApp.controller("yuktix.admin.lake.create", function ($scope, lake, $window) {

        $scope.initLakeData = function () {

            $scope.allLakeUsages.push({"id" : "1", "value" : "Walking","name" : "usageCode"});
            $scope.allLakeUsages.push({"id" : "2", "value" : "Birding","name" : "usageCode"});
            $scope.allLakeUsages.push({"id" : "3", "value" : "Fishing","name" : "usageCode"});
            $scope.allLakeUsages.push({"id" : "4", "value" : "Idol Immersion","name" : "usageCode"});
            $scope.allLakeUsages.push({"id" : "5", "value" : "Swimming","name" : "usageCode"});
            $scope.allLakeUsages.push({"id" : "6", "value" : "Livestock","name" : "usageCode"});
            $scope.allLakeUsages.push({"id" : "7", "value" : "Drinking","name" : "usageCode"});
            $scope.allLakeUsages.push({"id" : "8", "value" : "Other","name" : "usageCode"});

            $scope.allLakeTypes.push({"id" : "1", "name" : "Storm Water Fed"});
            $scope.allLakeTypes.push({"id" : "2", "name" : "Sewage Fed"});
            $scope.allLakeTypes.push({"id" : "3", "name" : "Mixed Inflow Fed"});

            $scope.allLakeAgencies.push({"id": "1", "name" : "BBMP"}) ;
            $scope.allLakeAgencies.push({"id": "2", "name" : "LDA"}) ;
            $scope.allLakeAgencies.push({"id": "3", "name" : "BDA"}) ;



        };

        $scope.select_agency = function(agency) {

            $scope.lakeObj.agencyCode = agency.id ;
            $scope.selectedAgency = agency ;

        } ;

        $scope.select_lake_type = function(lakeType) {

            $scope.lakeObj.typeCode = lakeType.id ;
            $scope.selectedLakeType = lakeType ;

        } ;

        $scope.toggle_usage_code = function(code) {

            var idx = $scope.lakeObj.usageCode.indexOf(code);

            if (idx > -1) {
                // already selected: turn off
                $scope.lakeObj.usageCode.splice(idx, 1);
            } else {
                // new selection
                $scope.lakeObj.usageCode.push(code);
            }

        };

        $scope.create_lake = function () {

            var errorObject = $scope.createForm.$error;
            if ($scope.validateForm(errorObject)) {
                return;
            }

            $scope.showProgress("submitting data to server");
            if ($scope.debug) {
                console.log("form values");
                console.log($scope.lakeObj);
            }

            // lake factory
            lake.create($scope.base, $scope.debug, $scope.lakeObj)
                .then(function (response) {

                    var status = response.status || 500;
                    var data = response.data || {};

                    if ($scope.debug) {
                        console.log("API response :");
                        console.log(data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log("browser response object: %o" ,response);
                        var error = data.error || (status + ":error while submitting data ");
                        $scope.showError(error);
                        return;
                    }

                    $window.location.href = "/admin/view/lake/list.php";

                }, function (response) {
                    $scope.processResponse(response);
                });


        };


        // data initialization
        $scope.lakeObj = {};
        $scope.lakeObj.usageCode = [] ;

        $scope.allLakeUsages = [] ;
        $scope.allLakeTypes= [];
        $scope.allLakeAgencies = [] ;

        $scope.initLakeData() ;

        // @todo needs index check
        $scope.selectedAgency = $scope.allLakeAgencies[0] ;
        $scope.selectedLakeType = $scope.allLakeTypes[0] ;
        $scope.lakeObj.agencyCode = $scope.selectedAgency.id ;
        $scope.lakeObj.typeCode = $scope.selectedLakeType.id ;

        $scope.errorMessage = "";

        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug;
        $scope.base = $scope.gparams.base;


    });
</script>

</body>
</html>