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
        <title> Lake images upload/edit page </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap-theme.min.css"/>
		<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto"/>
		<link rel="stylesheet" href="/assets/bootstrap/css/style.css"/>

    </head>

    <body  ng-controller="yuktix.admin.lake.image.upload">

        <div>
            <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-header.inc'); ?>
            
            <main>
                <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-progress.inc'); ?>

                <div class="container">
                
                    <div class="row row-padding">
                        <div  class="col-md-12" >
                            <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-page-message.inc'); ?>
                        </div>
                    </div> <!-- grid:1 -->
                    
                    <div class="row">
                        <div  class="col-md-6" >
                            <form name="csvUploadForm">
                                <h3><a href="/admin/lake/edit.php?lake_id=<?php echo $lakeId; ?>"> <span class="glyphicon glyphicon-arrow-left"></span> {{lakeObj.name}} </a> </h3>
                                </br>
                                <div>
                                    <label class="btn btn-primary">
                                       
                                        <span class="glyphicon glyphicon-camera"></span>
                                        <span>
                                            Select photos<input type="file" filelist-bind class="none"  name="files" style="display: none;" multiple>
                                        </span>
                                        
                                    </label>
                                </div>
                                </br>
                                <div>
                                    <ul>
                                        <li ng-repeat="file in files">
                                            <span>
                                                <span> {{file.name}} </span>
                                                <span>{{file.size/1000}} KB</span>
                                                &nbsp;
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
                        </div>
                    </div> <!-- grid:2 -->

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div  class="col-md-6">
                            <!-- display images in cards --> 
                            <div class="card" ng-repeat="image in images">
                                <figure class="card-img-top">
                                    <img class="img-responsive" ng-src="{{base}}/admin/shim/download/file.php?id={{image.fileId}}" alt="" />
                                </figure>
                                <div class="card-block">
                                    <h1 class="card-title">{{image.fileId}}</h1>
                                    <div class="card-text">
                                        <a ng-click="set_wallpaper(image.fileId)">Set Wallpaper</a>
                                        </br>    
                                        <a href="#">Delete</a>
                                        
                                    </div>
                                    
                                </div>
                            </div> <!-- card --> 
                        </div>
                    </div> <!-- grid:3 --> 
        
                   
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
	<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/js/angular.min.js"></script>
	<script src="/assets/js/main.js"></script>



    <script>
        
        yuktixApp.controller("yuktix.admin.lake.image.upload", function ($scope, $q, $window, lake, fupload) {

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
                        $scope.get_lake_images() ;

                    },function(response) {
                        $scope.processResponse(response);
                    });

            };

            $scope.get_lake_images = function() {

                $scope.showProgress("getting lake images from the server...");
                lake.getImages($scope.base,$scope.debug, $scope.lakeId).then( function(response) {
                        var status = response.status || 500;
                        var data = response.data || {};

                        if($scope.debug) {
                            console.log("server response:: lake images :%O", data);
                        }

                        if (status != 200 || data.code != 200) {
                            console.log(response);
                            var error = data.error || (status + ":error retrieving  data from server");
                            $scope.showError(error);
                            return;
                        }

                        $scope.images = data.result || {} ;
                        if($scope.debug) {
                            console.log("lake images :: %O", $scope.images);
                        }
                        
                        $scope.clearPageMessage();

                    },function(response) {
                        $scope.processResponse(response);
                    });

            };

            $scope.set_wallpaper  = function(imageFileId)  {

                lake.setWallpaper($scope.base,$scope.debug, $scope.lakeId,imageFileId)
                .then(function(response) {
                        var status = response.status || 500;
                        var data = response.data || {};
                        if($scope.debug) {
                            console.log("server response:: set wallpaper image :%O", data);
                        }

                        if (status != 200 || data.code != 200) {
                            console.log(response);
                            var error = data.error || (status + ":error retrieving  data from server");
                            $scope.showError(error);
                            return;
                        }

                        var message = "image: " + imageFileId + " has been set as wallpaper!" 
                        $scope.showMessage(message);
                        $window.alert(message);
                        return ;

                    },function(response) {
                        $scope.processResponse(response);
                    });

            } ;

            $scope.store_images = function() {
                
                console.log("file upload:: final callback...");
                if($scope.debug) {
                    console.log("storing:: lakeId and fileIds...");
                    console.log($scope.lakeId);
                    console.log($scope.fileIds);
                }

                lake.storeImages($scope.base,$scope.debug, $scope.lakeId, $scope.fileIds).then(function(response) {
                        var status = response.status || 500;
                        var data = response.data || {};
                        if($scope.debug) {
                            console.log("server response:: image store :%O", data);
                        }

                        if (status != 200 || data.code != 200) {
                            console.log(response);
                            var error = data.error || (status + ":error retrieving  data from server");
                            $scope.showError(error);
                            return;
                        }

                        var message = "images  uploaded successfully!" 
                        $scope.showMessage(message);
                        $window.alert(message);
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
                if($scope.file_counter == 0) {
                    $scope.store_images() ;
                }

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

                $scope.file_counter = $scope.files.length ;
                var promises = [];

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
                    console.log("file upload:: all done...");

                }); 

            };


            /*
            $scope.$on('$viewContentLoaded', () => {
                $timeout(() => {
                    componentHandler.upgradeAllRegistered();
                })
            }); */

            $scope.errorMessage = "" ;
            // page params
            $scope.gparams = <?php echo json_encode($gparams); ?> ;
            $scope.debug = $scope.gparams.debug;
            $scope.base = $scope.gparams.base;
            $scope.lakeId = <?php echo $lakeId ?> ;

            // data initialization
            $scope.lakeObj = {};
            $scope.file_counter = 0 ;
            $scope.fileIds = [] ;
            $scope.images = [] ;

            // display data init 
            $scope.display = {} ;
            $scope.display.lakeEditMenu = {} ;
            $scope.display.lakeEditMenu.image = true ;
            
            // sample data 
            $scope.samples = [] ;
            $scope.actionId = "button1";

            
            // start:
            $scope.get_lake_object() ;
        

        });
    </script>




</html>