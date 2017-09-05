<?php  

    include ("lake-app.inc");
    include(APP_WEB_DIR.'/inc/header.inc');

    use \com\indigloo\Url ;

    $gparams = new \stdClass ;
    $gparams->debug = false ;
    $gparams->base = Url::base() ;

    $lakeId = Url::tryQueryParam("lake_id") ;
        if(empty($lakeId)) {
            echo "<h1> lake_id is missing in request </h1>" ;
            exit ;
    }

    if(array_key_exists("jsdebug", $_REQUEST)) {
        $gparams->debug = true ;
    }

?>
<html ng-app="YuktixApp">
    
    <head>
        <title> Lake Features list </title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.css">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.css" />
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/style.css" />
        <link rel="stylesheet" href="/assets/css/main.css">
        
    </head>

    <body ng-controller="yuktix.admin.lake.feature.list">

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
                            
                            <div class="card" style="width: 40rem; border-color: black;" ng-show="display.notable">
                                <img class="card-img-top" src="/assets/images/dog.png" style="background-color: pink">
                                <div class="card-block">    
                                    <h4 class="card-title"> No Lake Features Found! </h4>
                                    <p class="card-text">
                                        We did not find any lake features in the system. 
                                        </br>
                                        Please click on the create button to get started. 
                                    </p>
                                    <button ng-click="goto_create()" class="btn btn-primary">
                                            Create a lake feature
                                    </button>
                                </div>

                            </div> <!-- no content card -->

                            <div class="table-container" ng-show="display.table">
                                <div ng-repeat="feature in features" class="card" style="width: 40rem; border-color: black;">
                                    <div class="card-block"> 
                                        <div class="card-header">
                                            <div class='btn-toolbar pull-right'>
                                                <div class='btn-group'>
                                                    <button class="glyphicon glyphicon-plus" ng-click="goto_create()"></button>
                                                </div>
                                            </div>
                                        </div> 
                                        <h3 class="card-title" ng-bind="feature.name"></h3>
                                        <div class="card--text" >
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <span class="glyphicon glyphicon-record">
                                                <font face="Times New Roman">{{feature.iocodeValue}} / {{feature.featureTypeValue}}</font>
                                                </span>
                                            </li>
                                            <li class="list-group-item">
                                                <span class="glyphicon glyphicon-map-marker">
                                                <font face="Times New Roman">{{feature.lat}}, {{feature.lon}}</font>
                                                </span>
                                                <span class="pull-right"><b>Location</b></span>
                                            </li>
                                            <li class="list-group-item"> 
                                                <span class="glyphicon glyphicon-th-large">
                                                <font face="Times New Roman">{{feature.width}},{{feature.maxHeight}}</font>
                                                </span> 
                                                <span class="pull-right"><b>Width/Height</b></span>
                                            </li>
                                            <li class="list-group-item"> 
                                                <span class="glyphicon glyphicon-eye-open">
                                                <font face="Times New Roman">{{feature.monitoringValue}}</font>
                                                </span>
                                                <span class="pull-right"><b>Monitoring</b></span>
                                            </li>
                                        </ul>
                                        
                                    
                                        <div class="card-footer">
                                            <button class="glyphicon glyphicon-pencil" ng-click="goto_edit(feature.id)"></button>
                                            &nbsp;
                                            <button class="glyphicon glyphicon-trash"></button>
                                        </div>
                                    </div> 
                                </div> <!-- card --> 
                            </div> <!-- display table: container -->  

                                
                            

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

        yuktixApp.controller("yuktix.admin.lake.feature.list",function($scope,lake,feature,$window) {

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
                    if($scope.features.length > 0 ) {
                        $scope.display.notable = false ;
                        $scope.display.table = true ;

                    } else {

                        $scope.display.notable = true ;
                        $scope.display.table = false ;

                    }

                    // translate codes 
                    var index = 0 ;
                    var featureObj = {} ;
                    var featureTypeValue ;
                    var featureMonitoringValue ;

                    for(index =0; index < $scope.features.length ; index++) {

                    featureObj = $scope.features[index];
                    lake.assignFeatureCodeValues($scope.codeMap, featureObj);
                    $scope.features[index]= featureObj ;

                    }

                    if($scope.debug) { 
                        console.log("lake feature with assigned code values::", $scope.features);
                    }

                    $scope.clearPageMessage();
                    
                },function(response) {
                    $scope.processResponse(response);
                });

            };


            $scope.goto_create = function() {
                $window.location.href = "/admin/lake/feature/create.php?lake_id="+ $scope.lakeId ;
            };

            $scope.goto_edit=function(featureId){
                $window.location.href = "/admin/lake/feature/edit.php?lake_id="+ $scope.lakeId + "&feature_id=" + featureId ;
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
                        $scope.codeMap =  data.result  ;
                        $scope.clearPageMessage();
                        $scope.get_lake_object() ;

                    },function(response) {
                        $scope.processResponse(response);
                    });
            };

            // set page parameters
            $scope.gparams = <?php echo json_encode($gparams); ?> ;
            $scope.debug = $scope.gparams.debug ;
            $scope.base = $scope.gparams.base ;

            // init data
            $scope.lakeId = <?php echo $lakeId ; ?> ;
            $scope.codeMap = {} ;

            $scope.features = {} ;
            $scope.errorMessage = "";

            // init display: table or no table
            $scope.display = {} ;
            $scope.display.notable = false ;
            $scope.display.table = false ;
            
            // lake edit menu display 
            $scope.display.lakeEditMenu = {} ;
            $scope.display.lakeEditMenu.feature = true ;

            $scope.init_codes();

        });


    </script>



</html>