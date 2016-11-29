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
<html  ng-app="YuktixApp">
<head>
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/mdl-selectfield.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        .mdl-layout1 {
            width: 100%;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>
<body  ng-controller="yuktix.admin.lake.edit">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <div class="mdl-layout-spacer"></div>
            <?php include (APP_WEB_DIR.'/inc/title.inc'); ?>
            <div class="mdl-layout-spacer"></div>

        </div>
    </header>
    <?php include(APP_WEB_DIR . '/inc/drawer.inc'); ?>
    <main class="mdl-layout__content">
        <div class="page-content">
            <div class="pad-bottom"></div>
            <?php include(APP_WEB_DIR . '/inc/page_error.inc'); ?>

            <!-- card -->
            <div class="mdl-grid">

                <div class="mdl-layout-spacer"></div>

                <div class="mdl-cell mdl-cell--6-col mdl-shadow--4dp">

                    <div class="mdl-card__title formcard mdl-color-text--indigo">
                        <h2 class="mdl-card__title-text formcard">Edit Lake</h2>
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
                                        ng-options="lakeType.value for lakeType in allLakeTypes"
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
                                            name="usageCode" required>

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
                                            ng-options="agency.value for agency in allLakeAgencies"
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

    yuktixApp.controller("yuktix.admin.lake.edit", function ($scope, lake, $window) {


        $scope.initCodes = function() {


            $scope.showProgress("Getting data from Server...");


            // contact user factory
            lake.getCodes($scope.base,$scope.debug)
                .then( function(response) {

                    var status = response.status || 500;
                    var data = response.data || {};


                    if($scope.debug) {
                        console.log("server response:: codes:%O", data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log(response);
                        var error = data.error || (status + ":error retrieving  data from Server");
                        $scope.showError(error);
                        return;

                    }

                    // @todo : check for property names
                    // before doing data binding
                    $scope.allLakeAgencies = data.result.lakeAgencies ;
                    $scope.allLakeTypes = data.result.lakeTypes ;
                    $scope.allLakeUsages = data.result.lakeUsages ;

                    // @todo check array length before data binding
                    $scope.selectedAgency = $scope.allLakeAgencies[0] ;
                    $scope.lakeObj.agencyCode = $scope.selectedAgency.id ;

                    $scope.selectedLakeType = $scope.allLakeTypes[0] ;
                    $scope.lakeObj.typeCode = $scope.selectedLakeType.id ;

                    $scope.clearPageMessage();

                },function(response) {
                    $scope.processResponse(response);
                });

        };


        //factory for submitting form data





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

        $scope.errorMessage = "";
        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug;
        $scope.base = $scope.gparams.base;

        //data initialization
        $scope.lakeObj = {};
        $scope.lakeObj.usageCode = [] ;
        $scope.allLakeAgencies = [] ;
        $scope.allLakeTypes = [] ;
        $scope.allLakeUsages = [] ;


        $scope.lakeCodes= {};
        $scope.initCodes();





    });
</script>
</body>
</html>