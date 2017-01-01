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
    <link rel="stylesheet" href="/assets/mdl/material.min.css" />
    <link rel="stylesheet" href="/assets/mdl/material.light_green-pink.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />

</head>

<body ng-controller="yuktix.admin.lake.feature.edit">

    <div class="mdl-layout mdl-js-layout" id="container">
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
        <main class="docs-layout-content mdl-layout__content ">
            <?php include(APP_WEB_DIR . '/inc/ui/mdl-progress.inc'); ?>
            <div class="mdl-grid">
                <?php include(APP_WEB_DIR . '/inc/ui/mdl-edit-sidebar.inc'); ?>
                <div class="mdl-cell mdl-cell--1-col"> </div>
                <div class="mdl-cell mdl-cell--6-col container-810">
                    <?php include(APP_WEB_DIR . '/inc/ui/mdl-page-message.inc'); ?>
                    
                    <form name="createForm">
                        <h5>Edit inlet/outlet </h5>
                        
                        <h6>Feature Name </h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="name" id="name" ng-model="featureObj.name" required>
                        
                        </div>
                         
                        
                        <h6> Feature Type </h6>
                        <div>
                            <select id="feature_type_select"
                                    ng-model="featureType"
                                    ng-change="select_feature_type(featureType)"
                                    ng-options="featureType.value for featureType in featureTypes">
                            </select>
                        </div>
                            
                        <h6>Latitude</h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="lat" id="lat" ng-model="featureObj.lat">
                        </div>
                            
                        <h6>Longitude</h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="lon" id="lon" ng-model="featureObj.lon" >
                            
                        </div>

                        <h6>Width </h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="width" id="width" ng-model="featureObj.width">
                        </div>
                            
                        <h6>Max. Height</h6>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" name="maxHeight" id="height" ng-model="featureObj.maxHeight" >
                        </div>
                    </form>
                        
                      
                    <p> This feature is using {{featureMonitoring.value}} </p>
                    <h5> Monitoring status </h5>
                    <div id="monitoring-container" class="mdl-tabs mdl-js-tabs">
                        <div class="mdl-tabs__tab-bar">
                            <a class="mdl-tabs__tab" ng-class="{'is-active':display.tabs.sensor}" ng-click="select_monitoring_tab(1)" href="#sensor-panel">Sensor</a>
                            <a class="mdl-tabs__tab" ng-class="{'is-active':display.tabs.lake}" ng-click="select_monitoring_tab(2)" href="#lake-panel">Lake Level</a>
                            <a class="mdl-tabs__tab" ng-class="{'is-active':display.tabs.constant}" ng-click="select_monitoring_tab(3)" href="#rate-panel">Constant</a>

                        </div>

                        <div class="mdl-tabs__panel" ng-class="{'is-active':display.tabs.sensor}" id="sensor-panel">
                            
                            <form name="sensorForm">
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
                                    <input class="mdl-textfield__input" type="text" name="installerName"  ng-model="featureObj.sensor.installerName">
                                </div>
                                <br>

                                <h6>Install date</h6>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" name="installationDate"  ng-model="featureObj.sensor.installationDate">
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
                            </form>

                        </div> <!-- tab:sensor -->

                        <div class="mdl-tabs__panel" ng-class="{'is-active':display.tabs.lake}" id="lake-panel">
                            <form name="lakeForm">
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
                            </form>
                        </div> <!-- tab:lake -->

                        <div class="mdl-tabs__panel" ng-class="{'is-active':display.tabs.constant}" id="rate-panel">
                             <form name="constantForm">
                                <h6>Flow rate</h6>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" name="flowRate"  ng-model="featureObj.flowRate">
                                </div>
                            </form>

                        </div> <!-- tab: constant -->

                    </div> <!-- monitoring -->
                       
                    
                    <div>
                        <button class="mdl-button mdl-js-button mdl-button--raised"ng-click="update_feature()" type="submit">
                        Save information
                        </button>
                    </div> 

                </div> 
                    
            </div> <!-- grid:content -->
            
            <div class="mdl-grid mdl-grid--no-spacing">
                <div class="mdl-cell mdl-cell--12-col">
                    <?php include(APP_WEB_DIR . '/inc/ui/mdl-footer.inc'); ?>
                </div>

        </div> <!-- footer -->


        </main>
        
</div> <!-- container div -->

</body>



<script src="/assets/mdl/material.min.js"></script>
<script src="/assets/js/angular.min.js"></script>
<script src="/assets/js/main.js"></script>

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
                    }

                    // we receive feature_type_code from the API
                    // we need to lookup and assign corresponding object 
                    // in list of all feature type code objects

                    var index = lake.findObjectOnCode($scope.featureTypes, $scope.featureObj.featureTypeCode, $scope.debug);
                    if (index == -1) {
                        console.error("select feature type code not assigned: revert to default");
                        index = 0 ;
                    }

                    // bind feature type 
                    $scope.featureType = $scope.featureTypes[index];

                    if($scope.featureMonitorings.length == 0) {
                        console.error("server did not return  monitoring codes");
                    }

                    index = lake.findObjectOnCode($scope.featureMonitorings, $scope.featureObj.monitoringCode, $scope.debug);
                    if (index == -1) {
                        console.error("monitoring  code not assigned: revert to default");
                        index = 0 ;
                    }

                    // bind feature monitoring code 
                    $scope.featureMonitoring = $scope.featureMonitorings[index];
                    
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

        
        $scope.lookup_feature_monitoring = function(code) {

            var index = lake.findObjectOnCode($scope.featureMonitorings, code, $scope.debug);
            if(index == -1) {
                console.error("No feature monitoring object found for code :%d", code);
                index = 0 ; 
            }

            $scope.featureMonitoring = $scope.featureMonitorings[index];
            
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

                    // show error 
                    $scope.showError(error);
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

            // main form errors
            var errorObject = $scope.createForm.$error;
            if ($scope.validateForm(errorObject)) {
                return;
            }

            if($scope.featureMonitoring.id == 1) {
                // sensor form errors
                errorObject = $scope.sensorForm.$error;
                if ($scope.validateForm(errorObject)) {
                    return;
                }
            }

            if($scope.featureMonitoring.id == 2) {
                // lake form errors
                errorObject = $scope.lakeForm.$error;
                if ($scope.validateForm(errorObject)) {
                    return;
                }
            }

            if($scope.featureMonitoring.id == 3) {
                // constant form errors
                errorObject = $scope.constantForm.$error;
                if ($scope.validateForm(errorObject)) {
                    return;
                }
            }

            if ($scope.debug) {
                console.log("form values");
                console.log($scope.featureObj);
                console.log($scope.fileUploadData);
                
            }

            $scope.showProgress("submitting data to server");
           
            // upload files depending on monitoring code , get fileId
            // send featureObj + file data to API  
            // 
            
            feature.update($scope.base, $scope.debug, $scope.featureObj, $scope.fileUploadData).then(function (response) {

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

                    $scope.showMessage("lake feature edited successfully!");
                    $window.location.href = "/admin/lake/feature/list.php?lake_id=" + $scope.lakeId + "&feature_id=" + data.featureId;

                }, function (response) {
                    $scope.processResponse(response);
                }); 

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
        
        // lake edit menu display 
        $scope.display.lakeEditMenu = {} ;
        $scope.display.lakeEditMenu.feature = true ;
        
        $scope.featureMonitorings = [] ;
        $scope.featureTypes = [] ;
        $scope.fileUploadData = {} ;

        $scope.get_feature_object() ;


    });


</script>

</html>



