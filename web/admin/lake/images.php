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
    <link rel="stylesheet" href="/assets/mdl/material.min.css" />
    <link rel="stylesheet" href="/assets/mdl/material.light_green-pink.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css?v=3" />
    <style>

    .mdl-card__media {
	margin: 0;
}
.mdl-card__media > img {
	max-width: 100%;
}
.mdl-card__actions {
	display: flex;
	box-sizing:border-box;
	align-items: center;
}
.mdl-card__actions > .mdl-button--icon {
	margin-right: 3px;
	margin-left: 3px;
}


</style>

</head>

<body  ng-controller="yuktix.admin.lake.image.upload">

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


                        <form name="csvUploadForm">
                            <h3> Upload images </h3>
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
                                            
                                            <span> {{file.name}} </span>
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
                         
                            
                        <!-- display images in cards --> 
                        <div class="mdl-card wide-mdl-card mdl-shadow--2dp" ng-repeat="image in images">
                            <figure class="mdl-card__media">
                                <img ng-src="{{base}}/admin/shim/download/file.php?id={{image.fileId}}" alt="" />
                            </figure>
                            <div class="mdl-card__title">
                                <h1 class="mdl-card__title-text">Learning Web Design</h1>
                            </div>
                           
							<div class="mdl-card__actions mdl-card--border"> 
                                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Read More</a>
                                <!-- <div class="mdl-layout-spacer"></div> -->

					            <button id="{{actionId}}" class="mdl-button mdl-button--icon mdl-button--colored"><i class="material-icons">share</i></button>
				                <ul class="mdl-menu mdl-menu--top-right mdl-js-menu" for="{{actionId}}">
                                    <li class="mdl-menu__item">Facebook</li>
                                    <li class="mdl-menu__item">Twitter</li>
                                    <li class="mdl-menu__item">Pinterest</li>
                                </ul>
	                        </div>
                        </div> <!-- card --> 


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
<script src="/assets/js/main.js?v=6"></script>



<script>

    yuktixApp.run(function () {
			
			var mdlUpgradeDom = false;
			setInterval(function() {
                if (mdlUpgradeDom) {
                    componentHandler.upgradeDom();
                    mdlUpgradeDom = false;
                }
			}, 200);

			var observer = new MutationObserver(function () {
			    mdlUpgradeDom = true;
			});
			observer.observe(document.body, {
				childList: true,
				subtree: true
			});
			
	});


    yuktixApp.controller("yuktix.admin.lake.image.upload", function ($scope, lake, fupload,$window) {

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

        $scope.store_images = function() {
            
            if($scope.debug) {
                console.log("submitting: lakeId and image fileIds...");
                console.log($scope.lakeId);
                console.log($scope.fileIds);
            }

            lake.storeImages($scope.base,$scope.debug, $scope.lakeId, $scope.fileIds)
            .then(function(response) {
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

        $scope.upload_file = function (uploadUrl,metadata, index, callback) {
            
            if($scope.debug) {
                console.log("processing file at index: %d", index);
            }

            var payload = new FormData();
            var xfile = $scope.files[index] ;

            payload.append("myfile", xfile);
            payload.append("metadata", angular.toJson(metadata));
            
            $scope.showProgress("uploading file...");
            fupload.send_mpart($scope.debug, uploadUrl, payload).then(function (response) {

                var status = response.status || 500;
                var data = response.data || {};

                if ($scope.debug) {
                    console.log("API response: %O", data);
                }

                if (status != 200 || data.code != 200) {
                    console.error("browser response object: %O" ,response);
                    var error  = data.error || (status + ":error while submitting data "); 
                    // halt file upload processing!
                    $scope.showError(error);
                    $window.alert(error) ;
                    return ;
                }

                $scope.fileIds.push(data.fileId);
                $scope.clearPageMessage();

                if(index == 0) {
                    if($scope.debug){ 
                        console.log("file upload done. over to callback ...");
                    }

                    callback();
                }

                return ;

            }, function (response) {
                $scope.processResponse(response);
            });
            
        };

        $scope.process_upload = function () {

            $scope.fileIds = [] ;
            if(!angular.isDefined($scope.files)) {
                // no files on page.
                var error = "no files found. please select a file first!";
                $scope.showError(error);
                $window.alert(error);
                return ;
            }

            var total = $scope.files.length ;
            var uploadUrl = $scope.base + "/admin/shim/upload/mpart.php" ;
            var metadata = { "store" : "database" } ;
            for(var i = total-1 ; i >= 0 ; i--) { 
                $scope.upload_file(uploadUrl, metadata,i, $scope.store_images);
            }
           

        };

        $scope.$on('$viewContentLoaded', () => {
            $timeout(() => {
                componentHandler.upgradeAllRegistered();
            })
        });

        $scope.errorMessage = "" ;
        // page params
        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug;
        $scope.base = $scope.gparams.base;
        $scope.lakeId = <?php echo $lakeId ?> ;

        // data initialization
        $scope.lakeObj = {};
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