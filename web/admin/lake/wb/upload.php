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
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.css">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.css" />
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/style.css" />
        <link rel="stylesheet" href="/assets/css/main.css">
    </head>

    <body  ng-controller="yuktix.admin.lake.wb.upload">

        <div>
            <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-header.inc'); ?>
           
            
            <main>
                <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-progress.inc'); ?>

                <div class="container">
                
                    <div class="row" style="padding:50px">
                        <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-edit-sidebar.inc'); ?>
                        <div class="col-md-1"> </div>
                        <div  class="col-md-6" id ="content">
                            <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-page-message.inc'); ?>

                            <h5> Features </h5>
                            <p>
                                Please select a feature by clicking on radio button.
                                Upload level and calibration file for selected feature.
                            </p>
                            <ul class="list-group">
                                <li class="list-group-item" ng-repeat="feature in features">
                                    <span>
                                        <span class="glyphicon glyphicon-tint"></span>
                                        <span>{{feature.name}} </span>
                                        <span>
                                            {{feature.details}}
                                        </span>
                                        
                                    </span>
                                    <span>
                                        
                                        <a href="#" ng-click="select_feature(feature)"> 
                                            <span class="glyphicon glyphicon-ok-sign"></span>
                                        </a>
                                    </span>
                                    
                                </li>
                            </ul>
                            <form class="form-group">
                                <div>
                                    <span> Data for</span>
                                    <span ng-bind="selectedFeature.name"> </span>

                                </div>

                                <div class="upload-button-container">
                                    <label>
                                    <span class="glyphicon glyphicon-file"></span>
                                    <font face="Times New Roman">Select File</font><input type="file" filelist-bind class="none"  name="files" style="display: none;" multiple>
                                    </label>
                                </div>
                                
                                <div>
                                    <ul class="list-group">
                                        <li class="list-group-item" ng-repeat="file in files">
                                            <span>
                                                
                                                <span> {{ file.name}} </span>
                                                <span>{{file.size/1000}} KB</span>
                                                
                                            </span>
                                            <span class="glyphicon glyphicon-check"></span>

                                        </li>
                                    </ul>
                                </div>

                                <div class="upload-button-container" ng-show="files.length > 0 ">
                                    <button class="btn btn-primary" ng-click="process_file_uploads()" type="submit">
                                        Upload 
                                    </button>
                                </div>
                            </form>
                        
                            <div style="padding-top:30px;" ng-if="preview.errors.length > 0 || preview.snapshots.length > 0">
                            
                                <p> <span ng-bind="preview.errors"> </span> </p> 
                                <div ng-repeat="snapshot in preview.snapshots">
                                    <ul class="list-group">
                                        <li>
                                            <span ng-bind="file_id_to_name(snapshot.fileId)"> </span>
                                            &nbsp;(<span ng-bind="file_code_to_name(snapshot.fileCode)"> </span>)
                                            
                                            <span ng-bind="snapshot.columns"> </span>
                                            <span ng-bind="snapshot.dateColumns"> </span>
                                            <span ng-bind="snapshot.numericColumns"> </span>

                                        </li>

                                        <li ng-repeat="row in snapshot.rows">
                                            <span> {{row}} </span>
                                        </li>
                                    
                                    </ul>

                                    
                                </div>

                                <div  class="upload-button-container">
                                    <button class="btn btn-primar" ng-click="confirm_upload()" type="submit">
                                        Confirm 
                                    </button>
                                    <button class="btn btn-primar" ng-click="cancel_upload()" type="submit">
                                        Cancel 
                                    </button>

                                </div>

                            </div> <!-- confirm upload --> 
                        

                        </div>
                    </div>
                </div> <!-- grid:content -->
        
                <div class="row">
                    <div class="col-md-12">
                        <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-footer.inc'); ?>
                    </div>

                </div> <!-- footer -->

            </main>
        
        
        </div> 
    </body>


    <script src="/assets/js/jquery-2.1.1.min.js"></script>
    <script src="/test/bootstrap/assets/js/bootstrap.js"></script>
    <script src="/test/bootstrap/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/angular.min.js"></script>
    <script src="/assets/js/main.js"></script>


    <script>

        yuktixApp.controller("yuktix.admin.lake.wb.upload", function ($scope,$q,$window, $timeout, lake, fupload, feature) {

            $scope.file_code_to_name = function(code) {

                var name = "__unknown__" ;
                var map = $scope.codeMap.featureFileCodes ;
                for(var i = 0; i < map.length; i++  ) {
                    if(map[i].id == code) {
                        return map[i].value ;
                    }
                }

                return name ;

            };

            $scope.file_id_to_name = function(xid) {

                return $scope.fileNames[xid]  ;

            };

            $scope.confirm_upload = function () {

                $scope.showProgress("uploading feature data to the server...");
                
                var postData = {} ;
                postData.lakeId = $scope.lakeId ;
                postData.featureId = $scope.selectedFeature.id ;
                postData.ioCode = $scope.selectedFeature.iocode ;
                postData.fileId = -1 ;
                postData.calibrationFileId = -1 ;

                for (var i = 0 ; i < $scope.preview.snapshots.length ; i++) {

                    var snapshot = $scope.preview.snapshots[i] ;
                    console.log("examine uploaded file: %O", snapshot);
                    
                    if(snapshot.fileCode == 2 ) {
                        postData.calibrationFileId = snapshot.fileId ;
                    }

                    if(snapshot.fileCode == 3 ) {
                        postData.fileId = snapshot.fileId ;
                    }

                }

                if($scope.debug) {
                    console.log("confirm feature upload: data :%O", postData);
                }

                feature.confirmUpload(
                    $scope.base,
                    $scope.debug, 
                    postData).then(function(response) {

                    var status = response.status || 500;
                    var data = response.data || {};
                    if($scope.debug) {
                        console.log("server response:: feature data upload:%O", data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log(response);
                        var error = data.error || (status + ":error retrieving  data from the server");
                        $scope.showError(error);
                        $scope.showToastMessage(error);
                        return;
                    }

                    $scope.showToastMessage(data.response);
                    $scope.showMessage(data.response); 
                    $timeout($scope.reload_page,3000);
                    
                    return ;

                },function(response) {
                    $scope.processResponse(response);
                });

                
            };

            $scope.reload_page = function() {
                $scope.showToastMessage("reloading the page...") ;
                $window.location.href = $scope.base + "/admin/lake/wb/upload.php?lake_id=" + $scope.lakeId ;
            }; 

            $scope.cancel_upload = function () {
                $scope.showToastMessage("cancelling...") ;
                $timeout($scope.reload_page,2000);
                
            };

            $scope.preview_upload = function() {
                
                console.log("file upload:: final callback");
                $scope.showProgress("uploading files to the server...");

                if($scope.debug) {
                    console.log("submitting: files for preview...");
                    console.log($scope.fileIds);
                }

                feature.previewUpload($scope.base,$scope.debug, $scope.fileIds).then(function(response) {

                    var status = response.status || 500;
                    var data = response.data || {};
                    if($scope.debug) {
                        console.log("server response:: preview feature data files:%O", data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log(response);
                        var error = data.error || (status + ":error retrieving  data from server");
                        $scope.showError(error);
                        $scope.showToastMessage(error);
                        return;
                    }

                    $scope.preview = {} ;
                    $scope.preview.errors = data.result.errors || [] ; 
                    $scope.preview.snapshots = data.result.snapshots || [] ;
                    $scope.clearPageMessage() ;
                    
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
                $scope.fileNames[data.fileId] = data.name ;
                $scope.file_counter = $scope.file_counter - 1 ;

                if($scope.file_counter == 0 ) {
                    $scope.preview_upload() ;
                }

                // console.log($scope.fileIds);
                return ;

            };

            $scope.process_file_uploads = function () {

                if(!angular.isDefined($scope.files)) {
                    // no files on page.
                    var error = "no files found. please select a file first!";
                    $scope.showError(error);
                    $scope.showToastMessage(error);
                    return ;
                }

                if($scope.files.length > 2) {
                    // more than 2 files not supported!
                    var error = "Only two files (data and calibration) can be uploaded at a time!";
                    $scope.showError(error);
                    $scope.showToastMessage(error);
                    return ;
                }

                var promises = [];
                $scope.fileData = {} ;
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
                        featureObj.details = "{featureType} at location: [{lat},{lon}] / {monitoring}" ;
                        featureObj.details = featureObj.details.supplant({
                            "featureType" : featureObj.featureTypeValue, 
                            "lat" : featureObj.lat,
                            "lon" : featureObj.lon,
                            "monitoring" : featureObj.monitoringValue 
                        }); 
                        
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
                        "id": 0 ,
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
            $scope.fileNames = {} ;

            $scope.preview = {} ;
            $scope.preview.errors = [] ;
            $scope.preview.snapshots = [] ;

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