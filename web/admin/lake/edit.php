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
        echo "<h1> required parameter id is missing </h1>" ;
        exit(1);
    }

    if (array_key_exists("jsdebug", $_REQUEST)) {
        $gparams->debug = true;
    }

?>
<html  ng-app="YuktixApp">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.css">
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.css" />
        <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/test/bootstrap/assets/css/style.css" />
        <link rel="stylesheet" href="/assets/css/main.css">
    </head> 

    <body  ng-controller="yuktix.admin.lake.edit">

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
                            
                            <div class="login-style">
                                <form name="createForm">
                            
                                    <div class="form-group">
                                        <h6>Name</h6>
                                        <input class="form-control" type="text" name="name" id="name" ng-model="lakeObj.name" required>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <h6>Latitude</h6>
                                        <input class="form-control" type="text" id="lat" name="latitude" ng-model="lakeObj.lat" required>
                                    </div>
                                    <br> 

                                    <div class="form-group">
                                        <h6>Longitude</h6>
                                        <input class="form-control" type="text" id="lon" name="longtitude" ng-model="lakeObj.lon" required>
                                    
                                    </div>
                                    <br>
                                    <div class="btn-group">
                                        <button type="button" class="glyphicon glyphicon-camera"></button>
                                        <a href="/admin/lake/images.php?lake_id=<?php echo $lakeId; ?>"><span>Set Lake Wallpaper</span></a>
                                    </div>

                                    <h6> Lake Type </h6>

                                    <div>
                                        <select id="lake_type_select"
                                            ng-model="lakeType"
                                            ng-change="select_lake_type(lakeType)"
                                            ng-options="lakeType.value for lakeType in lakeTypes">
                                        </select>
                                    
                                    </div>
                                    <br>
                                
                                    <div class="form-group">
                                        <textarea class="form-control" type="text" rows="5" id="about" name="about" ng-model="lakeObj.about" required></textarea>
                                
                                    </div>
                                    <br>
                                

                                    <div class="form-group">
                                        <h6>Address</h6>
                                        <textarea class="form-control" type="text" rows="3" id="address" name="address" ng-model="lakeObj.address" required></textarea>
                                
                                    </div>
                                    <br>


                                    <div class="form-group">
                                        <h6>Max Area</h6>
                                        <input class="form-control" type="text" id="area" name="maxArea" ng-model="lakeObj.maxArea" required>
                                    
                                    </div>
                                    <br>


                                    <div class="form-group">
                                        <h6>Max Volume</h6>
                                        <input class="form-control" type="text" id="volume" name="maxVolume" ng-model="lakeObj.maxVolume" required>
                                
                                    </div>
                                    <br>

                                    <div class="form-group">
                                        <h6>Recharge Rate</h6>
                                        <input class="form-control" type="text" id="recharge_rate" name="rechargeRate" ng-model="lakeObj.rechargeRate" required>
                                
                                    </div>
                                    <br>

                                    <h6> Agency</h6> 
                                    <div>
                                        <select id="agency_select" name="agency"
                                            ng-model="lakeAgency"
                                            ng-change="select_agency(lakeAgency)"
                                            ng-options="agency.value for agency in lakeAgencies"
                                            required>
                                        </select>
                                    </div>
                                    <br>

                                    <div class="usage-container">
                                        <h6> Usage </h6>
                                    
                                        <div ng-repeat="usage in lakeUsages">
                                            <div class="checkbox">
                                                <label for="{{usage.id}}">
                                                    <input type="checkbox" id="{{usage.id}}" ng-checked="lakeObj.usageCode.indexOf(usage.id) > -1" ng-click="toggle_usage_code(usage.id)" value="{usage.value}" name="usageCode" />
                                                    <span ng-bind="usage.value"></span>
                                                </label>
                                            </div>
                                        </div> 
                                    </div> <!-- usage -->
                                
                                    <div class="form-button-container">
                                        <button class="btn btn-primary" ng-click="update_lake()" type="submit">
                                            Save Lake information 
                                        </button>
                                    </div>

                                </form> 
                            </div>
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
	<script src="/test/bootstrap/assets/js/npm.js"></script>
	<script src="/test/bootstrap/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/angular.min.js"></script>
    <script src="/assets/js/main.js"></script>

    <script>

        
        yuktixApp.controller("yuktix.admin.lake.edit", function ($scope, lake, $window) {

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
                        $scope.clearPageMessage();
                        $scope.init_codes() ;

                    },function(response) {
                        $scope.processResponse(response);
                    });

            };


            $scope.init_codes = function() {

                $scope.showProgress("Getting required codes from server...");
                lake.getCodes($scope.base,$scope.debug)
                    .then( function(response) {

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

                        // @todo : check for property names
                        // before doing data binding
                        // @todo check array length before data binding
                        $scope.lakeAgencies = data.result.lakeAgencies ;
                        $scope.lakeTypes = data.result.lakeTypes ;
                        $scope.lakeUsages = data.result.lakeUsages ;
                        
                        // bind code to objects 
                        var index = lake.findObjectOnCode($scope.lakeAgencies, $scope.lakeObj.agencyCode, $scope.debug);
                        if(index == -1) {
                            console.error("error: no lake agency found for code: %d",$scope.lakeObj.agencyCode);
                            index = 0 ;
                        }

                        $scope.lakeAgency = $scope.lakeAgencies[index];

                        index = lake.findObjectOnCode($scope.lakeTypes, $scope.lakeObj.typeCode, $scope.debug);
                        if(index == -1) {
                            console.error("error: no lake type found for code: %d",$scope.lakeObj.typeCode);
                            index = 0 ;
                        }

                        $scope.lakeType = $scope.lakeTypes[index];
                        $scope.clearPageMessage();

                    },function(response) {
                        $scope.processResponse(response);
                    });

            };

            $scope.select_agency = function(agency) {
                $scope.lakeAgency = agency ;
            } ;

            $scope.select_lake_type = function(lakeType) {
                $scope.lakeType = lakeType ;
            } ;

            $scope.toggle_usage_code = function(code) {

                var idx = $scope.lakeObj.usageCode.indexOf(code);

                if (idx > -1) {
                    // already selected: turn off
                    $scope.lakeObj.usageCode.splice(idx, 1);
                } else {
                    // new selection
                    $scope.lakeObj.usageCode.push(code);
                }

            };

            $scope.update_lake = function () {

                var errorObject = $scope.createForm.$error;
                if ($scope.validateForm(errorObject)) {
                    return;
                }

                // bind select and radio fields
                $scope.lakeObj.agencyCode = $scope.lakeAgency.id ;
                $scope.lakeObj.typeCode = $scope.lakeType.id ;

                $scope.showProgress("submitting lake data to server");
                if ($scope.debug) {
                    console.log("form values");
                    console.log($scope.lakeObj);
                }

                lake.update($scope.base, $scope.debug, $scope.lakeObj).then(function (response) {

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
                            $window.alert(error);

                            return;
                        }
                        
                        
                        var message = "Lake details updated successfully!" ;
                        $window.scrollTo(0,0) ;
                        $scope.showMessage(message);
                        $window.alert(message);

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
            $scope.lakeObj.usageCode = [] ;

            // display data 
            $scope.display = {} ;
            // lake edit menu display 
            $scope.display.lakeEditMenu = {} ;
            $scope.display.lakeEditMenu.general = true ;

            $scope.get_lake_object() ;
            
        

        });


    

    </script>

</html>