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

$featureId = Url::tryQueryParam("feature_id");
if(empty($featureId)) {
    echo "<h1> required parameter feature_id is missing </h1>" ;
    exit(1);
}

if (array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true;
}

?>

<!DOCTYPE html>
<html ng-app="YuktixApp">

<head>

    <title> Lake Feature edit page </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_green-amber.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css">

</head>

<body ng-controller="yuktix.admin.lake.feature.edit">

    <div class="mdl-layout mdl-js-layout" id="container">
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
        <main class="docs-layout-content mdl-layout__content ">
            <div class="mdl-grid mdl-grid--no-spacing">
                <?php include(APP_WEB_DIR . '/inc/ui/mdl-edit-sidebar.inc'); ?>
                <div class="mdl-cell mdl-cell--1-col"> </div>
                <div id="content" class="mdl-grid mdl-cell mdl-cell--8-col">
                    <?php include(APP_WEB_DIR . '/inc/ui/page-error.inc'); ?>
                    <form name="createForm">
                         <h5>Edit inlet/outlet </h5>
                        
                        <h6>Feature Name </h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="name" id="name" ng-model="featureObj.name" required>
                           
                        </div>
                        <br>
                        
                        <h6> Feature Type </h6>
                         <div>
                            <select id="feature_type_select"
                                    ng-model="featureType"
                                    ng-change="select_feature_type(featureType)"
                                    ng-options="featureType.value for featureType in featureTypes">
                            </select>
                        </div>
                        <br>
                        
                        <h6>Latitude</h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="lat" id="lat" ng-model="featureObj.lat">
                        </div>
                        <br>

                        <h6>Longitude</h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="lon" id="lon" ng-model="featureObj.lon" >
                            
                        </div>
                        <br>

                        <h6>Width </h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="width" id="width" ng-model="featureObj.width">
                        </div>
                        <br>

                        <h6>Max. Height</h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="height" id="height" ng-model="featureObj.maxHeight" >
                        </div>
                        <br>
                     
                        
                        <p> This feature is using {{featureMonitoring.value}} </p>
                        <h5> Monitoring status </h5>
                        <div id="monitoring-container" class="mdl-tabs mdl-js-tabs">
                            <div class="mdl-tabs__tab-bar">
                                <a class="mdl-tabs__tab" ng-class="{'is-active':display.tabs.sensor}" ng-click="select_monitoring_tab(1)" href="#sensor-panel">Sensor</a>
                                <a class="mdl-tabs__tab" ng-class="{'is-active':display.tabs.lake}" ng-click="select_monitoring_tab(2)" href="#lake-panel">Lake Level</a>
                                <a class="mdl-tabs__tab" ng-class="{'is-active':display.tabs.constant}" ng-click="select_monitoring_tab(3)" href="#rate-panel">Constant</a>

                            </div>

                            <div class="mdl-tabs__panel" ng-class="{'is-active':display.tabs.sensor}" id="sensor-panel">
                            
                                <h6>Serial Number</h6>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" name="serialNumber"  ng-model="featureObj.sensor.serialNumber" required>
                                </div>
                                <br>

                                <h6>Part Number</h6>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" name="partNumber"  ng-model="featureObj.sensor.partNumber">
                                </div>
                                <br>

                                <h6>Installed by</h6>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" name="serialNumber"  ng-model="featureObj.sensor.installerName">
                                </div>
                                <br>

                                <h6>Install date</h6>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" name="serialNumber"  ng-model="featureObj.sensor.installDate">
                                </div>
                                <br>

                                <h6> Sensor stage flow</h6>
                                <div>
                                    <label class="mdl-button mdl-button--colored mdl-js-button">
                                        <span> <i class="material-icons">attachment</i> </span>
                                        Upload CSV <input type="file" filelist-bind class="none"  name="files" style="display: none;">
                                    </label>
                                </div>
                                <br>
                                <div>
                                    <ul class="mdl-list">
                                        <li "mdl-list__item" ng-repeat="file in files">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-icon">insert_drive_file</i>
                                                {{ file.name}}, {{file.size/1000}} kb
                                            </span>
                                        
                                        </li>
                                    </ul>
                                </div>
                                

                            </div> <!-- tab:sensor -->

                            <div class="mdl-tabs__panel" ng-class="{'is-active':display.tabs.lake}" id="lake-panel">
                                <h6> Lake stage flow</h6>
                                <div>
                                      <label class="mdl-button mdl-button--colored mdl-js-button">
                                        <span> <i class="material-icons">attachment</i> </span>
                                        Upload CSV <input type="file" filelist-bind class="none"  name="files" style="display: none;">
                                    </label>
                                </div>
                                <br>
                                <div>
                                    <ul class="mdl-list">
                                        <li "mdl-list__item" ng-repeat="file in files">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-icon">insert_drive_file</i>
                                                {{ file.name}}, {{file.size/1000}} kb
                                            </span>
                                        
                                        </li>
                                    </ul>
                                </div>
                            </div> <!-- tab:lake -->

                            <div class="mdl-tabs__panel" ng-class="{'is-active':display.tabs.constant}" id="rate-panel">
                                <p>Third tab's content.</p>
                            </div> <!-- tab: constant -->

                        </div> <!-- monitoring -->

                        <div class="form-button-container">
                            <button class="mdl-button mdl-js-button mdl-button--raised"ng-click="update_feature()" type="submit">
                            Save information
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
<script src="/assets/js/main.js?v=1"></script>

<script>

    // UI expectations:
    // 1. select the monitoring panel tab automatically 
    // $scope.display.tabs.sensor | lake | constant 
    // 2. select the lake feature type 
    // $scope.featureType
    // 3. on tab selection - change the feature monitoring state 
    // $scope.featureMonitor
    // 
    // 
    yuktixApp.controller("yuktix.admin.lake.feature.edit", function ($scope, lake, feature,fupload,$window) {

          $scope.get_feature_object = function() {

            $scope.showProgress("getting feature object from server...");
            feature.getFeatureObject($scope.base,$scope.debug, $scope.featureId).then( function(response) {
                    var status = response.status || 500;
                    var data = response.data || {};
                    if($scope.debug) {
                        console.log("server response:: feature object:%O", data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.error(response);
                        var error = data.error || (status + ":error retrieving  data from Server");
                        $scope.showError(error);
                        return;
                    }

                    $scope.featureObj = data.result ;
                    $scope.clearPageMessage();
                    $scope.init_codes() ;

                },function(response) {
                    $scope.processResponse(response);
                });

        };

        $scope.init_codes = function() {

            $scope.showProgress("getting codes from Server...");
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

                    if($scope.featureTypes.length == 0) {
                        console.error("server did not return  feature type codes");
                        return ;
                    }

                    // we receive feature_type_code from the API
                    // we need to lookup and assign corresponding object 
                    // in list of all feature type code objects
                    $scope.featureType = {} ;
                    $scope.lookup_feature_type($scope.featureObj.featureTypeCode);

                    if(angular.equals($scope.featureType, {})) {
                        console.error("select feature type code not assigned: revert to default");
                        $scope.featureType = $scope.featureTypes[0];
                    }

                    if($scope.featureMonitorings.length == 0) {
                        console.error("server did not return  monitoring codes");
                        return ;
                    }

                    //  we receive monitoring_code from the API
                    //  we need to lookup and assign corresponding object 
                    // in list of all feature monitoring state objects
                    $scope.featureMonitoring = {} ;
                    $scope.lookup_feature_monitoring($scope.featureObj.monitoringCode);

                    if(angular.equals($scope.featureMonitoring, {})) {
                        console.error("monitoring  code not assigned: revert to default");
                        $scope.featureMonitoring = $scope.featureMonitorings[0];
                    }
                    
                    if($scope.debug) {
                        console.log("selected feature type=%O", $scope.featureType);
                        console.log("selected monitoring =%O", $scope.featureMonitoring);
                    }

                    $scope.select_monitoring_tab($scope.featureMonitoring.id) ;
                    $scope.clearPageMessage();

                },function(response) {
                    $scope.processResponse(response);
                });

        };

        $scope.select_feature_type = function(featureType) {
            $scope.featureType = featureType ;
        } ;

         $scope.lookup_feature_type = function(code) {

            for (var i = 0 ; i < $scope.featureTypes.length; i++) {
                if($scope.debug) {
                    console.log("feature type code: comparing %O with %d",$scope.featureTypes[i], code);
                }

                if($scope.featureTypes[i].id == code) {
                    $scope.featureType = $scope.featureTypes[i];
                }
            }
        };

        $scope.lookup_feature_monitoring = function(code) {

            for (var i = 0 ; i < $scope.featureMonitorings.length; i++) {
                if($scope.debug) {
                    console.log("feature monitoring code: comparing %O with %d",$scope.featureMonitorings[i],code);
                }

                if($scope.featureMonitorings[i].id == code) {
                    $scope.featureMonitoring = $scope.featureMonitorings[i];
                }
            }
        };

        $scope.select_monitoring_tab = function(code) {

             // turn on/off display tabs
            switch(code) {
                case 1 :
                    $scope.display.tabs.sensor = true ;
                    $scope.display.tabs.lake = false ;
                    $scope.display.tabs.constant = false ;
                    break ;

                case 2 :
                    $scope.display.tabs.sensor = false ;
                    $scope.display.tabs.lake = true ;
                    $scope.display.tabs.constant = false ;
                    break ;

                case 3 :
                    $scope.display.tabs.sensor = false ;
                    $scope.display.tabs.lake = false ;
                    $scope.display.tabs.constant = true ;
                    break ;

                default :
                    console.error("unknown feature monitoring code...");
                    break ;
            }

            $scope.lookup_feature_monitoring(code);
            return ;      
        } ;

        /*
        for (var i = 0 ; i < $scope.files.length;  i++) {
            var xfile = $scope.files[i];
            if($scope.debug) {
                console.log("file name=%s, size=%d, type=%s",xfile.name, xfile.size, xfile.type);
            }

            $scope.upload_file(uploadUrl, metadata, xfile);

        } */

        $scope.upload_file = function (uploadUrl,metadata) {

            $scope.fileUploadData = {} ;
            $scope.fileUploadData.items = [] ;

            if(!angular.isDefined($scope.files)) {
                // no files on page.
                var xmsg = 
                    "no files found on the page! "
                    + " please check the you are using filelist-bind directive"
                    + " with input type = file and  name=files element." ; 

                console.error(xmsg);
                $scope.send_form_data() ;
                return ;
            }

            var payload = new FormData();
            var xfile = $scope.files[0] ;

            payload.append("myfile", xfile);
            payload.append("metadata", angular.toJson(metadata));
            
            $scope.showProgress("uploading file...");
            fupload.send_mpart($scope.debug, uploadUrl, payload).then(function (response) {

                var status = response.status || 500;
                var data = response.data || {};

                if ($scope.debug) {
                    console.log("API response :");
                    console.log(data);
                }

                if (status != 200 || data.code != 200) {

                    console.error("browser response object: " ,response);
                    var error  = data.error || (status + ":error while submitting data ");
                    $scope.fileUploadData.items.push({
                        "error" : error ,
                        "upload" : false 
                    });

                    return ;
                    
                }

                // success
                $scope.fileUploadData.items.push({
                    "upload" : true ,
                    "fileId" : data.fileId,
                    "name" : data.name 
                });
                
                $scope.send_form_data() ;
                return ;

            }, function (response) {
                $scope.processResponse(response);
            });
            
        };
        
        $scope.update_feature = function () {

            console.log("inside update_feature() ...");
            // assign feature type 
            $scope.featureObj.featureTypeCode = $scope.featureType.id ;
            // assign monitoring state code 
            $scope.featureObj.monitoringCode = $scope.featureMonitoring.id ;

            if(($scope.featureObj.monitoringCode == 1) 
                || ($scope.featureObj.monitoringCode == 2 )) {

                var uploadUrl = $scope.base + "/admin/shim/upload/mpart.php" ;
                var metadata = { 
                    "store" : "database"
                } ;

                // start with file upload
                $scope.upload_file(uploadUrl, metadata);
                
            } else {
                $scope.send_form_data() ;
            }

        }

        $scope.send_form_data = function () {

            var errorObject = $scope.createForm.$error;
            if ($scope.validateForm(errorObject)) {
                return;
            }


            $scope.showProgress("submitting data to server");
            if ($scope.debug) {
                console.log("form values");
                console.log($scope.featureObj);
                console.log($scope.fileUploadData);
                
            }

            // upload files depending on monitoring code , get fileId
            // send featureObj + monitoring code + fileId to API  
            // 
            /*
            feature.update($scope.base, $scope.debug, $scope.featureObj).then(function (response) {

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
                }); */

        };

        // page params
        $scope.errorMessage = "";
        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug;
        $scope.base = $scope.gparams.base;
        $scope.lakeId = <?php echo $lakeId ?> ;
        $scope.featureId = <?php echo $featureId ?> ;

        // data initialization
        $scope.featureObj = {};
        $scope.featureObj.lakeId = $scope.lakeId ;
        $scope.featureObj.id = $scope.featureId ;

        // display data 
        $scope.display = {} ;
        $scope.display.tabs = {
            "sensor" : false ,
            "lake" : false, 
            "constant" : true 
        } ;
        
        $scope.featureMonitorings = [] ;
        $scope.featureTypes = [] ;
        $scope.fileUploadData = {} ;

        $scope.get_feature_object() ;


    });


</script>

</html>




