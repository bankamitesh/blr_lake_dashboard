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
        echo "<h1> required parameter lake_id is missing </h1>" ;
        exit(1);
    }

    if (array_key_exists("jsdebug", $_REQUEST)) {
        $gparams->debug = true;
    }
    
?>

<!DOCTYPE html>

<html  ng-app="YuktixApp">
<head>
    <title> Lake water balance data upload page </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/mdl/material.min.css" />
    <link rel="stylesheet" href="/assets/mdl/material.light_green-pink.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css?v=3" />

</head>

<body  ng-controller="yuktix.admin.lake.wb.upload">

    <div class="mdl-layout mdl-js-layout">
        <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
        <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
        
        <main class="mdl-components__pages mdl-layout__content ">
            <?php include(APP_WEB_DIR . '/inc/ui/mdl-progress.inc'); ?>

                <div class="mdl-grid">
                    <?php include(APP_WEB_DIR . '/inc/ui/mdl-edit-sidebar.inc'); ?>
                    <div class="mdl-cell mdl-cell--1-col"> </div>
                    <div  class="mdl-cell mdl-cell--6-col container-810" >
                        <?php include(APP_WEB_DIR . '/inc/ui/mdl-page-message.inc'); ?>

                        <h5> Features </h5>
                        <p>
                            Please select a feature by clicking on radio button.
                            Upload level and calibration file for selected feature.
                        </p>
                        <ul class="feature-list mdl-list">
                            <li class="mdl-list__item mdl-list__item--three-line" ng-repeat="feature in features">
                                <span class="mdl-list__item-primary-content">
                                    <i class="material-icons">{{feature.icon1}}</i>
                                    <span>{{feature.name}} </span>
                                    <span class="mdl-list__item-text-body">
                                        {{feature.details}}
                                    </span>
                                    
                                </span>
                                <span class="mdl-list__item-secondary-content">
                                    
                                    <a href="#" ng-click="select_feature(feature)"> 
                                        <i class="material-icons">{{feature.icon2}}</i>
                                    </a>
                                </span>
                                
                            </li>
                        </ul>
                        <form class="upload-button-container">
                            <div>
                                <span> Data for</span>
                                <span ng-bind="selectedFeature.name"> </span>

                            </div>

                            <div>
                                <label class="mdl-button mdl-button--colored mdl-js-button">
                                    <span> <i class="material-icons">attach_file</i> </span>
                                    Select Files<input type="file" filelist-bind class="none"  name="files" style="display: none;" multiple>
                                </label>
                            </div>
                            
                            <div>
                                <ul class="mdl-list">
                                    <li class="mdl-list__item mdl-list__item--two-line" ng-repeat="file in files">
                                        <span class="mdl-list__item-primary-content">
                                            
                                            <span> {{ file.name}} </span>
                                            <span class="mdl-list__item-sub-title">{{file.size/1000}} kb</span>
                                            
                                        </span>
                                        <span class="mdl-list__item-secondary-content">
                                            <i class="material-icons">check</i>
                                        </span>

                                    </li>
                                </ul>
                            </div>

                            <div class="upload-button-container" ng-show="files.length > 0 ">
                                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" ng-click="process_upload()" type="submit">
                                    Upload 
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
<script src="/assets/js/main.js?v=7"></script>



<script>

    yuktixApp.controller("yuktix.admin.lake.wb.upload", function ($scope,$q,$window, lake, fupload, feature) {

        
        $scope.confirm_upload = function() {
            
            console.log("file upload:: final callback");
            if($scope.debug) {
                console.log("submitting: fileIds for water balance calculations");
                console.log($scope.fileIds);
            }

            feature.confirmUpload($scope.base,$scope.debug, $scope.fileIds).then(function(response) {

                    var status = response.status || 500;
                    var data = response.data || {};
                    if($scope.debug) {
                        console.log("server response:: confirm feature data upload :%O", data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log(response);
                        var error = data.error || (status + ":error retrieving  data from server");
                        $scope.showError(error);
                        $scope.showToastMessage(error);
                        return;
                    }

                    // show confirmation to user 
                    return ;

                },function(response) {
                    $scope.processResponse(response);
                });


        };

        
        $scope.handle_file_upload_success = function(response) {

            var status = response.status || 500;
            var data = response.data || {};
            if($scope.debug) {
                console.log("server response :: %O", data);
            }

            if (status != 200 || data.code != 200) {
                console.log(response);
                var error = data.error || (status + ": error from server");
                console.error(error);
                $window.alert(error);
                return;
            }

            $scope.fileIds.push(data.fileId); 
            $scope.file_counter = $scope.file_counter - 1 ;
            if($scope.file_counter == 0 ) {
                $scope.confirm_upload() ;
            }

            // console.log($scope.fileIds);
            return ;

        };

        $scope.process_upload = function () {

            if(!angular.isDefined($scope.files)) {
                // no files on page.
                var error = "no files found. please select a file first!";
                $scope.showError(error);
                $scope.showToastMessage(error);
                return ;
            }

            var promises = [];
            $scope.file_counter = $scope.files.length ;

            for (var i = 0;  i < $scope.files.length ; i++) {

                var apromise = fupload.send_file(
                    $scope.debug, 
                    $scope.base + "/admin/shim/upload/mpart.php" ,
                    $scope.files[i], 
                    { "store" : "database" },
                    $scope.handle_file_upload_success,
                    $scope.processResponse);

                promises.push(apromise);
            }
            
            $q.all(promises).then(function(){
                // final callback 
                console.log("all done...");

            }); 

        };

        
        $scope.get_lake_object = function() {

            $scope.showProgress("getting lake object from server...");
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
                    $scope.get_lake_features() ;

                },function(response) {
                    $scope.processResponse(response);
                });

        };

        $scope.get_lake_features = function() {

            $scope.showProgress("getting lakes features data from the server...");
            feature.list($scope.base,$scope.debug, $scope.lakeId) .then( function(response) {

                var status = response.status || 500;
                var data = response.data || {};

                if($scope.debug) {
                    console.log("server response:: lake features data:%O", data);
                }

                if (status != 200 || data.code != 200) {
                    console.log(response);
                    var error = data.error || (status + ":error retrieving  data from server");
                    $scope.showError(error);
                    return;

                }

                $scope.features = data.result ;
                
                // add data to features
                for( var i = 0 ; i < $scope.features.length; i++) {
                    var featureObj =  $scope.features[i] ;
                    // add code values to feature 
                    lake.assignFeatureCodeValues($scope.codeMap, featureObj);
                    // assign icons 
                    featureObj.icon1 = "place" ;
                    featureObj.icon2 = "radio_button_unchecked" ;
                    featureObj.details = featureObj.iocodeValue 
                                        + featureObj.featureTypeValue 
                                        + "Lat,Lon: [" 
                                        + featureObj.lat + "," + featureObj.lon 
                                        + "]  /" + featureObj.monitoringValue ;

                    $scope.features[i] = featureObj ; 
                    if($scope.debug) {
                        console.log("feature : object with assigned code values ::%O", featureObj);
                    }
                }

                // add Lake level as feature 
                // iocode 1: inlet
                // iocode 2: outlet 
                // iocode 3: lake level file 

                var xfeature = { 
                    "icon1" : "pool",
                    "icon2" : "radio_button_unchecked",
                    "iocode" : 3,
                    "name" : "Lake Level" ,
                    "details" : "manual measurement of lake levels" 
                    
                }

                $scope.features.push(xfeature);

                // default assignment 
                if($scope.features.length > 0) {
                    $scope.select_feature($scope.features[0]);
                }

                $scope.clearPageMessage();
                

            },function(response) {
                $scope.processResponse(response);
            });

        };

        $scope.select_feature = function(feature) {
            
            // uncheck other features 
            for(var i = 0 ; i < $scope.features.length; i++) {
                $scope.features[i].icon2 = "radio_button_unchecked" ;
            }

            feature.icon2 =  "radio_button_checked" ;
            $scope.selectedFeature = feature ;
        };  

        $scope.init_codes = function() {

            $scope.showProgress("getting required codes from server...");
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

                    // bind data 
                    $scope.codeMap = data.result ;
                    $scope.clearPageMessage();
                    $scope.get_lake_object() ;

                },function(response) {
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
        $scope.features = [] ;
        $scope.codeMap = {} ;

        $scope.file_counter = 0 ;
        $scope.fileIds = [] ;
        // display data initialization
        $scope.display = {} ;
       
        // lake edit menu display 
        $scope.display.lakeEditMenu = {} ;
        $scope.display.lakeEditMenu.waterBalance = true ;

        // sample data 
        $scope.samples = [] ;
        
        // start:
        $scope.init_codes() ;
    

    });
</script>




</html>