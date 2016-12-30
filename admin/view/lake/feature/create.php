<?php

    include("lake-app.inc");
    include(APP_WEB_DIR . '/inc/header.inc');

    use \com\indigloo\Url;

    $gparams = new \stdClass;
    $gparams->debug = false;
    $gparams->base = Url::base();
    $lakeId = Url::tryQueryParam("lake_id");
    if(empty($lakeId)) {
        echo "<h1> required parameter lake_id is missing </h1>" ;
        exit(1);
    }

    if (array_key_exists("jsdebug", $_REQUEST)) {
        $gparams->debug = true;
    }

?>

<!DOCTYPE html>
<html ng-app="YuktixApp">

<head>

    <title> Lake Feature create page </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/mdl/material.min.css" />
    <link rel="stylesheet" href="/assets/mdl/material.light_green-pink.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />


</head>

<body ng-controller="yuktix.admin.lake.feature.create">

    <div class="mdl-layout mdl-js-layout" id="container">
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
        <main class="docs-layout-content mdl-layout__content ">
            <div class="mdl-grid">
                <?php include(APP_WEB_DIR . '/inc/ui/mdl-edit-sidebar.inc'); ?>
                <div class="mdl-cell mdl-cell--1-col"> </div>
                <div  class="mdl-cell mdl-cell--8-col container-810">
                    <?php include(APP_WEB_DIR . '/inc/ui/mdl-page-message.inc'); ?>

                    <form name="createForm">
                         <h5>Create inlet/outlet </h5>
                        
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="name" id="name" ng-model="featureObj.name" required>
                            <label class="mdl-textfield__label" for="name">Feature Name </label>
                        </div>
                        <br>
                        
                        <h5> Feature Type </h5>
                         <div>
                            <select id="feature_type_select"
                                    ng-model="featureType"
                                    ng-change="select_feature_type(featureType)"
                                    ng-options="featureType.value for featureType in featureTypes">
                            </select>
                        </div>
                        <br>
                        
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="lat" id="lat" ng-model="featureObj.lat">
                            <label class="mdl-textfield__label" for="width">Latitude </label>
                        </div>
                        <br>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="lon" id="lon" ng-model="featureObj.lon" >
                            <label class="mdl-textfield__label" for="height">Longitude</label>
                        </div>
                        <br>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="width" id="width" ng-model="featureObj.width">
                            <label class="mdl-textfield__label" for="width">Width </label>
                        </div>
                        <br>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="height" id="height" ng-model="featureObj.maxHeight" >
                            <label class="mdl-textfield__label" for="height">Max. Height</label>
                        </div>
                        <br>

                        <h5> Monitoring status </h5>
                       
                        <div ng-repeat="monitoring in featureMonitorings">
                            <label for="option_{{monitoring.id}}" class="mdl-radio mdl-js-radio">
                                <input 
                                    type="radio" 
                                    id="option_{{monitoring.id}}" 
                                    ng-model ="selectedRadio1.id"
                                    name= "monitoring"
                                    class="mdl-radio__button" 
                                    value="{{monitoring.id}}">
                                <span class="mdl-radio__label" ng-bind="monitoring.value"></span>
                            </label>
                        </div>

                        <div class="form-button-container">
                            <button class="mdl-button mdl-js-button mdl-button--raised"ng-click="create_feature()" type="submit">
                            Save information
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

    yuktixApp.controller("yuktix.admin.lake.feature.create", function ($scope, lake, feature,$window) {

        $scope.init_codes = function() {
            $scope.showProgress("Getting codes from Server...");
            lake.getCodes($scope.base,$scope.debug).then( function(response) {

                    var status = response.status || 500;
                    var data = response.data || {};
                    if($scope.debug) {
                        console.log("server response:: codes:%O", data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.error(response);
                        var error = data.error || (status + ":error retrieving  data from Server");
                        $scope.showError(error);
                        return;
                    }

                    // @todo : error checks
                    // 1. check for property names in result
                    // 2. check for array length before data binding 
                    // 
                    $scope.featureTypes = data.result.featureTypes ;
                    $scope.featureMonitorings = data.result.featureMonitorings ;

                    // selected feature type
                    $scope.featureType = $scope.featureTypes[0] ;
                    // selected Radio button
                    $scope.selectedRadio1 = {
                        "id" : $scope.featureMonitorings[0].id  
                    }; 

                    $scope.clearPageMessage();

                },function(response) {
                    $scope.processResponse(response);
                });

        };

        
        $scope.create_feature = function () {

            var errorObject = $scope.createForm.$error;
            if ($scope.validateForm(errorObject)) {
                return;
            }

            // Assign radio and select box 
            $scope.featureObj.featureTypeCode = $scope.featureType.id ;
            $scope.featureObj.monitoringCode = $scope.selectedRadio1.id ;

            $scope.showProgress("submitting data to server");
            if ($scope.debug) {
                console.log("form values");
                console.log($scope.featureObj);
            }

            feature.create($scope.base, $scope.debug, $scope.featureObj).then(function (response) {

                    var status = response.status || 500;
                    var data = response.data || {};

                    if ($scope.debug) {
                        console.log("API response :");
                        console.log(data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log("browser response object: %o" ,response);
                        var error = data.error || (status + ":error submitting feature create form");
                        $scope.showError(error);
                        return;
                    }

                   $window.location.href = "/admin/view/lake/feature/edit.php?lake_id=" + $scope.lakeId + "&feature_id=" + data.featureId;

                }, function (response) {
                    $scope.processResponse(response);
                }); 

        };

        $scope.select_feature_type = function(featureType) {
            $scope.featureType = featureType ;
        } ;

        // page params
        $scope.errorMessage = "";
        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug;
        $scope.base = $scope.gparams.base;
        $scope.lakeId = <?php echo $lakeId ?> ;

        // data initialization
        $scope.featureObj = {};
        $scope.featureObj.lakeId = $scope.lakeId ;
        $scope.featureMonitorings = [] ;
        $scope.featureTypes = [] ;
        
        
        $scope.init_codes();

    });


</script>

</html>




