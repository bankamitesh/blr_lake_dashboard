<?php

include("lake-app.inc");
include(APP_WEB_DIR . '/inc/header.inc');

use \com\indigloo\Url;
use \com\yuktix\lake\auth\Login as Login ;

// role check
// redirect to login page
Login::isCustomerAdmin("/app/login.php") ;

$gparams = new \stdClass;
$gparams->debug = false;
$gparams->base = Url::base();

$lakeId = Url::tryQueryParam("lake_id");
if(empty($lakeId)) {
    echo "<h1> required parameter id is missing </h1>" ;
    exit(1);
}

if (array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true;
}

?>
<html  ng-app="YuktixApp">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="/assets/mdl/material.min.css">
     <link rel="stylesheet" href="/assets/mdl/material.light_green-pink.min.css" />
     <link rel="stylesheet" href="/assets/css/main.css">

</head>

<body  ng-controller="yuktix.admin.lake.edit">

<div class="mdl-layout mdl-js-layout" id="container">

    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="mdl-components__pages mdl-layout__content ">
        <?php include(APP_WEB_DIR . '/inc/ui/mdl-progress.inc'); ?>

        <div class="mdl-grid">
            <?php include(APP_WEB_DIR . '/inc/ui/mdl-edit-sidebar.inc'); ?>
            <div class="mdl-cell mdl-cell--1-col"> </div>
            <div id ="content" class="mdl-grid mdl-cell mdl-cell--8-col" >
                <?php include(APP_WEB_DIR . '/inc/ui/mdl-page-message.inc'); ?>
                
                    <form name="createForm" >
                        
                             
                            <div class="mdl-textfield mdl-js-textfield">
                                <h6>Name</h6>
                                <input class="mdl-textfield__input" type="text" name="name" id="name"
                                        ng-model="lakeObj.name" required>
                                
                            </div>
                            <br>
                                <h6>Latitude</h6>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="lat" name="latitude"
                                        ng-model="lakeObj.lat" required>
                               
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <h6>Longitude</h6>
                                <input class="mdl-textfield__input" type="text" id="lon" name="longtitude"
                                        ng-model="lakeObj.lon" required>
                                
                            </div>
                            <br>

                            <h6> Lake Type </h6>

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
                               
                            </div>
                            <br>

                            <div class="mdl-textfield mdl-js-textfield">
                                <h6>address</h6>
                                <textarea class="mdl-textfield__input" type="text" rows="3" id="address" name="address"
                                            ng-model="lakeObj.address" required></textarea>
                               
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <h6>max. area</h6>
                                <input class="mdl-textfield__input" type="text" id="area" name="maxArea"
                                        ng-model="lakeObj.maxArea" required>
                                
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <h6>max. volume</h6>
                                <input class="mdl-textfield__input" type="text" id="volume" name="maxVolume"
                                        ng-model="lakeObj.maxVolume" required>
                               
                            </div>
                            <br>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <h6>recharge rate</h6>
                                <input class="mdl-textfield__input" type="text" id="recharge_rate" name="rechargeRate"
                                        ng-model="lakeObj.rechargeRate" required>
                               
                            </div>
                            <br>

                            <h6> Agency</h6> 
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
                                <h6> Usage </h6>
                                
                                
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
                                <button class="mdl-button mdl-js-button mdl-button--raised"ng-click="update_lake()" type="submit">
                                    Save Lake information 
                                </button>
                            </div>

                        </form> 
                   </div>
                   
        </div> <!-- grid:content -->

        <div class="mdl-grid mdl-grid--no-spacing">
            <div class="mdl-cell mdl-cell--12-col">
                <?php include(APP_WEB_DIR . '/inc/ui/mdl-footer.inc'); ?>
            </div>

        </div> <!-- footer -->

    </main> 
 </div> 
</body>


<script src="/assets/mdl/material.min.js"></script>
<script src="/assets/js/angular.min.js"></script>
<script src="/assets/js/main.js"></script>


<script>

    yuktixApp.controller("yuktix.admin.lake.edit", function ($scope, lake, $window) {

         $scope.get_lake_object = function() {

            $scope.showProgress("Getting lake object from server...");
            lake.getLakeObject($scope.base,$scope.debug, $scope.lakeId).then( function(response) {
                    var status = response.status || 500;
                    var data = response.data || {};
                    if($scope.debug) {
                        console.log("server response:: lake object:%O", data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log(response);
                        var error = data.error || (status + ":error retrieving  data from Server");
                        $scope.showError(error);
                        return;
                    }

                    $scope.lakeObj = data.result ;
                    $scope.clearPageMessage();
                    $scope.init_codes() ;

                },function(response) {
                    $scope.processResponse(response);
                });

        };


        $scope.init_codes = function() {

            $scope.showProgress("Getting required codes from server...");
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
                    // @todo check array length before data binding
                    $scope.lakeAgencies = data.result.lakeAgencies ;
                    $scope.lakeTypes = data.result.lakeTypes ;
                    $scope.lakeUsages = data.result.lakeUsages ;
                    
                    // bind code to objects 
                    var index = lake.findObjectOnCode($scope.lakeAgencies, $scope.lakeObj.agencyCode, $scope.debug);
                    if(index == -1) {
                        console.error("error: no lake agency found for code: %d",$scope.lakeObj.agencyCode);
                        index = 0 ;
                    }

                    $scope.lakeAgency = $scope.lakeAgencies[index];

                    index = lake.findObjectOnCode($scope.lakeTypes, $scope.lakeObj.typeCode, $scope.debug);
                    if(index == -1) {
                        console.error("error: no lake type found for code: %d",$scope.lakeObj.typeCode);
                        index = 0 ;
                    }

                    $scope.lakeType = $scope.lakeTypes[index];
                    $scope.clearPageMessage();

                },function(response) {
                    $scope.processResponse(response);
                });

        };

        $scope.select_agency = function(agency) {
            $scope.lakeAgency = agency ;
        } ;

        $scope.select_lake_type = function(lakeType) {
            $scope.lakeType = lakeType ;
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

        $scope.update_lake = function () {

            var errorObject = $scope.createForm.$error;
            if ($scope.validateForm(errorObject)) {
                return;
            }

            // bind select and radio fields
            $scope.lakeObj.agencyCode = $scope.lakeAgency.id ;
            $scope.lakeObj.typeCode = $scope.lakeType.id ;

            $scope.showProgress("submitting lake data to server");
            if ($scope.debug) {
                console.log("form values");
                console.log($scope.lakeObj);
            }

            lake.update($scope.base, $scope.debug, $scope.lakeObj).then(function (response) {

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
                        $window.alert(error);

                        return;
                    }
                    
                    
                    var message = "Lake details updated successfully!" ;
                    $window.scrollTo(0,0) ;
                    $scope.showMessage(message);
                    $window.alert(message);

                }, function (response) {
                    $scope.processResponse(response);
                });
        };

        $scope.errorMessage = "" ;
        // page params
        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug;
        $scope.base = $scope.gparams.base;
        $scope.lakeId = <?php echo $lakeId ?> ;

        // data initialization
        $scope.lakeObj = {};
        $scope.lakeObj.usageCode = [] ;

        // display data 
        $scope.display = {} ;
        // lake edit menu display 
        $scope.display.lakeEditMenu = {} ;
        $scope.display.lakeEditMenu.general = true ;

        $scope.get_lake_object() ;
        
      

    });


 

</script>

</html>