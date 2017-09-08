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


<html  ng-app="YuktixApp">
    <head>
        <title> Lake evaporation data page </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.css">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.css" />
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/style.css" />
        <link rel="stylesheet" href="/assets/css/main.css">

    </head>

    <body  ng-controller="yuktix.admin.lake.csv.upload">

        <div>
            <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-header.inc'); ?>
            <main>
                    <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-progress.inc'); ?>
                    <div class="container">
                        <div class="row row-padding">
                            
                            
                            <div  class="col-md-6" id ="content">
                                <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-page-message.inc'); ?>
                                <form name="csvUploadForm" >
                                
                                    <p>
                                    Please upload the lake evaporation  data
                                    as comma separated values file (.csv). 
                                    First column is month of year and second column is evaporation in cubic meters (m^3).
                                    
                                    </p>
                                    <h5> Sample </h5>
                                    <ul class="list-group">
                                        <li class="list-group-item" ng-repeat="sample in samples">
                                            <span ng-bind="sample"></span>    
                                        </li>
                                    </ul>

                                    <div class="upload-button-container">
                                        <label>
                                            <span class="glyphicon glyphicon-file"></span>
                                            Select File<input type="file" filelist-bind class="none"  name="files" style="display: none;">
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
                                        <button class="btn btn-primary" ng-click="process_upload()" type="submit">
                                            Upload 
                                        </button>
                                    </div>
                                </form> 
                            
                                <div class="file-download-container" ng-show="display.downloadLink">
                                    <h6>System evaporation data file</h6>
                                    
                                    <span> 
                                        {{lakeFileObj.fileName}} ( {{lakeFileObj.createdOn}} )
                                    </span>
                                    <span class="glyphicon glyphicon-download-alt"></span>
                                    <a ng-href="{{base}}/admin/shim/download/file.php?id={{lakeFileObj.fileId}}">
                                        Download File  
                                    </a>
                                </div>

                                <div ng-show="!display.downloadLink">
                                    <h6> No evaporation data file in the system </h6>
                                </div>
                                

                            </div>
                            <div class="col-md-1"> </div>
                        <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-edit-sidebar.inc'); ?>
                        </div> <!-- grid:content -->
                    </div>
        
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

        yuktixApp.controller("yuktix.admin.lake.csv.upload", function ($scope, lake, fupload,$window) {

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
                        $scope.get_lake_file() ;

                    },function(response) {
                        $scope.processResponse(response);
                    });

            };

            $scope.get_lake_file = function() {

                $scope.showProgress("getting lake file object from server...");
                lake.getRelationshipFile($scope.base,$scope.debug, $scope.lakeId,$scope.fileCode).then( function(response) {
                        var status = response.status || 500;
                        var data = response.data || {};

                        if($scope.debug) {
                            console.log("server response:: lake file object:%O", data);
                        }

                        if (status != 200 || data.code != 200) {
                            console.log(response);
                            var error = data.error || (status + ":error retrieving  data from Server");
                            $scope.showError(error);
                            return;
                        }

                        $scope.lakeFileObj = data.result || {} ;
                        if($scope.debug) {
                            console.log("lake file obj ::", $scope.lakeFileObj);
                        }

                        // set display.downloadLink
                        if($scope.lakeFileObj.hasOwnProperty("fileId")) {
                            $scope.display.downloadLink = true ;
                            $scope.lakeFileObj.createdOn =  new Date($scope.lakeFileObj.tsUnix * 1000).toLocaleString() ;
                        }
                        
                        $scope.clearPageMessage();
                        

                    },function(response) {
                        $scope.processResponse(response);
                    });

            };

            $scope.upload_file = function (uploadUrl,metadata) {

                if(!angular.isDefined($scope.files)) {
                    // no files on page.
                    var error = "no files found. please select a file first!";
                    var xmsg = "no files found during processing. " 
                        + " please check the you are using filelist-bind directive"
                        + " with input type = file and  name=files element." ; 

                    $scope.showError(error);
                    console.error(xmsg);
                    return ;
                }

                var formData = new FormData();
                var xfile = $scope.files[0] ;

                formData.append("myfile", xfile);
                formData.append("metadata", angular.toJson(metadata));
                
                $scope.showProgress("uploading file...");

                // resolve file upload promise
                fupload.send_form_data($scope.debug, uploadUrl, formData).then(function (response) {

                    var status = response.status || 500;
                    var data = response.data || {};

                    if ($scope.debug) {
                        console.log("API response :");
                        console.log(data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.error("browser response object: " ,response);
                        var error  = data.error || (status + ":error while submitting data ");
                        $scope.showError(error);
                        $scope.showToastMessage(error);
                        return ;
                    }
                    
                    $scope.send_file_data(data.fileId) ;
                    return ;

                }, function (response) {
                    $scope.processResponse(response);
                });
                
            };

            $scope.send_file_data = function(fileId) {

                lake.storeRelationshipFile($scope.base, $scope.debug,$scope.lakeId, $scope.fileCode, fileId).then(function (response) {

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
                            $scope.showToastMessage(error);
                            return;
                        }

                        var message = "evaporation data file uploaded!" 
                        $scope.showToastMessage(message);
                        $scope.clearPageMessage();

                    }, function (response) {
                        $scope.processResponse(response);
                    }); 
            }

            $scope.process_upload = function () {

                var uploadUrl = $scope.base + "/admin/shim/upload/mpart.php" ;
                var metadata = { 
                    "store" : "database"
                } ;

                $scope.upload_file(uploadUrl, metadata);
            };

            $scope.errorMessage = "" ;
            // page params
            $scope.gparams = <?php echo json_encode($gparams); ?> ;
            $scope.debug = $scope.gparams.debug;
            $scope.base = $scope.gparams.base;
            $scope.lakeId = <?php echo $lakeId ?> ;

            // data initialization
            $scope.lakeObj = {};
            $scope.lakeFileObj = {} ;
            $scope.display = {} ;
            $scope.display.downloadLink = false ;

            // lake edit menu display 
            $scope.display.lakeEditMenu = {} ;
            $scope.display.lakeEditMenu.evaporation = true ;


            // file code: 1 stage-volume
            // file code: 2 stage-area
            // file code: 3 evaporation

            $scope.fileCode = 3 ;

            // sample evaporation data 
            $scope.samples = [] ;
            
            $scope.samples.push("January , 83685.11716");
            $scope.samples.push("February , 92708.48279");
            $scope.samples.push("March , 131164.4071");
            
            // start 
            $scope.get_lake_object() ;
        

        });
    </script>




</html>