<?php

    include("lake-app.inc");
    include(APP_WEB_DIR . '/inc/header.inc');

    use \com\indigloo\Url;
    use \com\yuktix\lake\auth\Login as Login ;
    use \com\yuktix\lake\mysql\Lake as Lake ;

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
    
    $zones = Lake::getZones($lakeId);

?>


<html  ng-app="YuktixApp">
    <head>
        <title> Lake zone page </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap-theme.min.css"/>
		<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto"/>
		<link rel="stylesheet" href="/assets/bootstrap/css/style.css"/>
    </head>

    <body  ng-controller="yuktix.admin.lake.zone">

        <div>

            <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-header.inc'); ?>
           
        
            <main >
                <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-progress.inc'); ?>

                <div class="container">
                
                    <div class="row row-padding">
                        
                        <div  class="col-md-6" id ="content">
                            <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-page-message.inc'); ?>
                            
                            <div class="login-style">
                                <form name="createForm">
                            
                            
                                    <p>
                                    To add zones and inlet/outlet markers on a map, 
                                    create the map and layers in google map. save the 
                                    html embed code together with a description.

                                    </p>
                                    <div class="form-group">
                                        <textarea class="form-control" type="text" name="html" id="html" rows="5"
                                                ng-model="lakeObj.zone.html" required>
                                        </textarea>
                                        <label for="html">paste html code here ...</label>
                                    </div>
                                
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="description" id="description"
                                                ng-model="lakeObj.zone.description">
                                        
                                        <label for="description">description</label>
                                    </div>
                                    <br>
                                    <button class="btn btn-primary" ng-click="save_zone()" type="submit">
                                        Save zone information 
                                    </button>

                                </form>
                            
                                <div class="zone-container">

                                    <?php foreach($zones as $zone) { ?>
                                    
                                        <div class="description">
                                            <?php echo $zone->description ; ?>
                                        </div>

                                        <div class="map">
                                            <?php echo $zone->html ; ?>
                                        </div>
                                    

                                        <div class="action">
                                            <button class="btn btn-primary" ng-click="remove_zone(<?php echo $zone->id ?>)" type="submit">
                                            remove this zone 
                                            </button>
                                        </div>

                                    <?php } ?> 
                                </div> <!-- zone container -->


                            </div>
                        </div>
                        <div class="col-md-1"> </div>
                        <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-edit-sidebar.inc'); ?>
                        
                    </div> 
                </div> <!-- grid:content -->

                <div class="row">
                    <div class="col-md-12">
                        <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-footer.inc'); ?>
                    </div>

                </div> <!-- footer -->

            </main>
            
        </div> <!-- container div -->

    </body>
    
    <script src="/assets/js/jquery-2.1.1.min.js"></script>
	<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/js/angular.min.js"></script>
	<script src="/assets/js/main.js"></script>

    <script>

        yuktixApp.controller("yuktix.admin.lake.zone", function ($scope, lake,$window) {

            $scope.get_lake_object = function() {

                $scope.showProgress("getting lake data from server...");
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

                    },function(response) {
                        $scope.processResponse(response);
                    });

            };

            $scope.save_zone = function() {

                if($scope.debug) {
                    console.log("lake_id for zone is",$scope.lakeObj.id);
                    console.log("zone object is ", $scope.lakeObj.zone);
                }

                lake.createZone($scope.base, $scope.debug, $scope.lakeObj.id, $scope.lakeObj.zone).then(function (response) {

                        var status = response.status || 500;
                        var data = response.data || {};

                        if ($scope.debug) {
                            console.log("API response :");
                            console.log(data);
                        }

                        if (status != 200 || data.code != 200) {
                            console.log("browser response object: %o" ,response);
                            var error = data.error || (status + ":error submitting lake create form");
                            $scope.showError(error);
                            return;
                        }

                        $window.location.href = "/admin/lake/zone.php?lake_id=" + $scope.lakeId ;

                    }, function (response) {
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
            $scope.lakeObj.zone = {} ;
            $scope.zones = [] ;

            // init display data 
            $scope.display = {} ;
            // lake edit menu display 
            $scope.display.lakeEditMenu = {} ;
            $scope.display.lakeEditMenu.zone = true ;


            $scope.get_lake_object() ;

        });


    </script>

    
    

</html>