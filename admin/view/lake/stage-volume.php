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
    <title> Lake stage volume edit page </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_green-amber.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body  ng-controller="yuktix.admin.lake.stage.volume">

<div class="mdl-layout mdl-js-layout" id="container">

    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="mdl-components__pages mdl-layout__content ">
        <div class="mdl-grid mdl-grid--no-spacing">
        <?php include(APP_WEB_DIR . '/inc/ui/mdl-edit-sidebar.inc'); ?>
            
            <div class="mdl-cell mdl-cell--1-col"> </div>
            <div id ="content" class="mdl-cell mdl-cell--4-col" >
                <?php include(APP_WEB_DIR . '/inc/ui/page-error.inc'); ?>
                
                <div class="form-container">
                    <form name="stageVolumeForm" >
                        
                            <h5>Upload stage volume data </h5>
                           
                            <p>
                            Please upload the lake stage volume data in CSV format.
                            *add a sample here *
                            </p>

                            <div>
                                <label class="mdl-button mdl-button--colored mdl-js-button">
                                    <span> <i class="material-icons">attachment</i> </span>
                                    Select File<input type="file" filelist-bind class="none"  name="files" style="display: none;">
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

                            <div class="form-button-container">
                                <button class="mdl-button mdl-js-button mdl-button--raised"ng-click="upload_file()" type="submit">
                                    Upload 
                                </button>
                            </div>
                        </form> 
                    </div> 

                    <div>
                        <h6> stage volume stored in the system </h6>
                        <a ng-href="{{base}}/admin/download/file.php?id={{lakeFileObj.id}}">download file</a>
                    </div>
                </div>

        </div> <!-- grid:1 -->
       
    </main>
    
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-footer.inc'); ?>

</div> <!-- container div -->

</body>


<script src="/assets/js/material.min.js"></script>
<script src="/assets/js/mdl-selectfield.min.js"></script>
<script src="/assets/js/angular.min.js"></script>
<script src="/assets/js/main.js?v=1"></script>


<script>

    yuktixApp.controller("yuktix.admin.lake.stage.volume", function ($scope, lake, fupload,$window) {

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
            lake.getFileObject($scope.base,$scope.debug, $scope.lakeId,$scope.fileCode).then( function(response) {
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

                    $scope.lakeFileObj = data.result ;
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
                    // show error 
                    $scope.showError(error);
                    return ;
                }
                
                $scope.send_file_data(data.fileId) ;
                return ;

            }, function (response) {
                $scope.processResponse(response);
            });
            
        };

        $scope.send_file_data = function(fileId) {

            lake.storeFile($scope.base, $scope.debug,$scope.lakeId, $scope.fileCode, $scope.fileId).then(function (response) {

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

                    $scope.showMessage("lake file data uploaded successfully!");
                    // @debug
                    // reload page
                    $window.location.href = "/admin/view/lake/stage-volume.php?lake_id=" + $scope.lakeId ;

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
        // file code: 1 stage-volume
        // file code: 2 stage-area
        // file code: 3 evaporation

        $scope.fileCode = 1 ;
        $scope.get_lake_object() ;
    

    });
</script>

</html>