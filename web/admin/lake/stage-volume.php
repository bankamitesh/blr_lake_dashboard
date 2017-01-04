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
    <title> Lake stage volume edit page </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/mdl/material.min.css" />
    <link rel="stylesheet" href="/assets/mdl/material.light_green-pink.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css?v=3" />

</head>

<body  ng-controller="yuktix.admin.lake.csv.upload">

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
                        <form name="csvUploadForm" >
                          
                            <p>
                            Please upload the lake stage volume relationship data
                            as comma separated values file (.csv). 
                            First column is stage in meters and second column is volume in cubic meters.
                            
                            </p>

                             <ul class="mdl-list mdl-shadow--2dp">
                                <li class="mdl-list__item" ng-repeat="sample in samples">
                                    <span class="mdl-list__item-primary-content" ng-bind="sample">  </span>    
                                </li>
                            </ul>

                            <div>
                                <label class="mdl-button mdl-button--colored mdl-js-button">
                                    <span> <i class="material-icons">attach_file</i> </span>
                                    Select File<input type="file" filelist-bind class="none"  name="files" style="display: none;">
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
                       
                        <div class="file-download-container" ng-show="display.downloadLink">
                            <h6>System stage volume relationship file</h6>
                            <a ng-href="{{base}}/admin/shim/download/file.php?id={{lakeFileObj.fileId}}">
                                <span> {{lakeFileObj.fileName}} ( {{lakeFileObj.createdOn}} )</span>
                                <i class="material-icons">file_download</i>
                                
                            </a>
                        </div>

                        <div ng-show="!display.downloadLink">
                            <h6>No stage volume relationship file in the system </h6>
                        </div>
                   

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
<script src="/assets/js/main.js?v=1"></script>



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

                    var message = "file uploaded. reload the page to see new file." 
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
        $scope.display.lakeEditMenu.stageVolume = true ;


        // file code: 1 stage-volume
        // file code: 2 stage-area
        // file code: 3 evaporation

        $scope.fileCode = 1 ;

        // sample stage-volume data 
        $scope.samples = [] ;
        
        $scope.samples.push("0.15 , 1317.281689");
        $scope.samples.push("0.65 , 27859.87955");
        $scope.samples.push("1.15 , 97549.57163");
        $scope.samples.push("1.65 , 217217.1273");
        

        


        $scope.get_lake_object() ;
    

    });
</script>




</html>