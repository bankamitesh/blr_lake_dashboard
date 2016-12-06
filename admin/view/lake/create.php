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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_green-amber.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body ng-controller="yuktix.admin.lake.create">

    <div class="mdl-layout mdl-js-layout" id="container">
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="mdl-components__pages mdl-layout__content">
        <div class="mdl-grid mdl-grid--no-spacing" id="content">
            
            <div class="mdl-cell mdl-cell--3-col"> </div>
            <div id="content" class="mdl-grid mdl-cell mdl-cell--9-col">
                 <?php include(APP_WEB_DIR . '/inc/ui/page-error.inc'); ?>
                <form name="createForm">
                    
                        <h5>Create a new lake </h5>
                        
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="name" id="name"
                                    ng-model="lakeObj.name" required>
                            <label class="mdl-textfield__label" for="name">Lake Name </label>
                        </div>
                        <br>
                        
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="lat" name="latitude"
                                    ng-model="lakeObj.lat" required>
                            <label class="mdl-textfield__label" for="lat">Latitude...</label>
                        </div>
                        <br>


                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="lon" name="longtitude"
                                    ng-model="lakeObj.lon" required>
                            <label class="mdl-textfield__label" for="lon">Longtitude...</label>
                        </div>
                        <br>

                        <h5> Lake Type </h5>

                        <div>
                            <select id="lake_type_select"
                                    ng-model="lakeType"
                                    ng-change="select_lake_type(lakeType)"
                                    ng-options="lakeType.value for lakeType in lakeTypes">
                            </select>
                            
                        </div>
                        <br>
                        
                            <div class="mdl-textfield mdl-js-textfield">
                            <textarea class="mdl-textfield__input" type="text" rows="5" id="about" name="about"
                                        ng-model="lakeObj.about" required></textarea>
                            <label class="mdl-textfield__label" for="about">About / provide a write up for the lake...</label>
                        </div>
                        <br>


                        <div class="mdl-textfield mdl-js-textfield">
                            <textarea class="mdl-textfield__input" type="text" rows="3" id="address" name="address"
                                        ng-model="lakeObj.address" required></textarea>
                            <label class="mdl-textfield__label" for="address">Address...</label>
                        </div>
                        <br>


                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="area" name="maxArea"
                                    ng-model="lakeObj.maxArea" required>
                            <label class="mdl-textfield__label" for="area">Max Area...</label>
                        </div>
                        <br>


                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="volume" name="maxVolume"
                                    ng-model="lakeObj.maxVolume" required>
                            <label class="mdl-textfield__label" for="volume">Max Volume...</label>
                        </div>
                        <br>


                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="recharge_rate" name="rechargeRate"
                                    ng-model="lakeObj.rechargeRate" required>
                            <label class="mdl-textfield__label" for="recharge_rate">Rechange Rate...</label>
                        </div>
                        <br>

                        <h5> Agency</h5> 
                        <div>
                            <select id="agency_select" name="agency"
                                    ng-model="lakeAgency"
                                    ng-change="select_agency(lakeAgency)"
                                    ng-options="agency.value for agency in lakeAgencies"
                                    required>
                            </select>
                        </div>
                        <br>

                        <div class="usage-container">
                            <h5> Usage </h5>
                            
                            
                                <div ng-repeat="usage in lakeUsages">
                                    <label for="{{usage.id}}" class="mdl-checkbox mdl-js-checkbox" >
                                        <input
                                            type="checkbox"
                                            id="{{usage.id}}" 
                                            class="mdl-checkbox__input "
                                            ng-checked="lakeObj.usageCode.indexOf(usage.id) > -1"
                                            ng-click="toggle_usage_code(usage.id)"
                                            value="{usage.value}"
                                            name="usageCode" />
                                            <span class="mdl-checkbox__label" ng-bind="usage.value"></span>
                                    </label>
                                </div> 

                        </div> <!-- usage -->
                        
                        <div class="form-button-container">
                            <button class="mdl-button mdl-js-button mdl-button--raised"ng-click="create_lake()" type="submit">
                                Save Lake information 
                            </button>
                        </div>

                    </form> 

        </div> <!-- grid -->
        
    </main>
    
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-footer.inc'); ?>

</div> <!-- container div -->

</body>

<script src="/assets/js/material.min.js"></script>
<script src="/assets/js/mdl-selectfield.min.js"></script>
<script src="/assets/js/angular.min.js"></script>
<script src="/assets/js/main.js"></script>

<script>

    yuktixApp.controller("yuktix.admin.lake.create", function ($scope, lake, $window) {

        $scope.init_codes = function() {

            $scope.showProgress("Getting codes from Server...");
            lake.getCodes($scope.base,$scope.debug).then( function(response) {
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
                    // @todo check array length before data binding

                    $scope.lakeAgencies = data.result.lakeAgencies ;
                    $scope.lakeTypes = data.result.lakeTypes ;
                    $scope.lakeUsages = data.result.lakeUsages ;

                    $scope.lakeAgency = $scope.lakeAgencies[0] ;
                    $scope.lakeType = $scope.lakeTypes[0] ;
                    $scope.clearPageMessage();

                },function(response) {
                    $scope.processResponse(response);
                });

        };

        $scope.select_lake_type = function(lakeType) {
            $scope.lakeType = lakeType ;
        } ;

        $scope.select_agency = function(agency) {
            $scope.lakeAgency = agency ;
        }

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

            // bind select and radio fields
            $scope.lakeObj.agencyCode = $scope.lakeAgency.id ;
            $scope.lakeObj.typeCode = $scope.lakeType.id ;

            if ($scope.debug) {
                console.log("form values");
                console.log($scope.lakeObj);
            }

            lake.create($scope.base, $scope.debug, $scope.lakeObj).then(function (response) {

                    var status = response.status || 500;
                    var data = response.data || {};

                    if ($scope.debug) {
                        console.log("API response :");
                        console.log(data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log("browser response object: %o" ,response);
                        var error = data.error || (status + ":error submitting lake create form");
                        $scope.showError(error);
                        return;
                    }

                    $window.location.href = "/admin/view/lake/list.php";

                }, function (response) {
                    $scope.processResponse(response);
                });


        };

        $scope.errorMessage = "";
        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug;
        $scope.base = $scope.gparams.base;

        // data initialization
        $scope.lakeObj = {};
        $scope.lakeObj.usageCode = [] ;
        $scope.lakeAgencies = [] ;
        $scope.lakeTypes = [] ;
        $scope.lakeUsages = [] ;

        $scope.lakeCodes= {};
        $scope.init_codes();

    });
</script>

</html>