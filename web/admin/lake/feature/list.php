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
    <link rel="stylesheet" href="/assets/mdl/material.min.css" />
    <link rel="stylesheet" href="/assets/mdl/material.light_green-pink.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />

    
</head>

<body ng-controller="yuktix.admin.lake.feature.list">

 <div class="mdl-layout mdl-js-layout" id="container">

    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="docs-layout-content mdl-layout__content ">
      <?php include(APP_WEB_DIR . '/inc/ui/mdl-progress.inc'); ?>

        <div class="mdl-grid">

           <?php include(APP_WEB_DIR . '/inc/ui/mdl-edit-sidebar.inc'); ?>
            <div class="mdl-cell mdl-cell--1-col"> </div>
            <div id="content" class="mdl-cell mdl-cell--6-col container-810">
                <?php include(APP_WEB_DIR . '/inc/ui/mdl-page-message.inc'); ?>
                <div class="mdl-card mdl-shadow--4dp no-table-card" ng-show="display.notable">
                 <h4 class="mdl-card__title"> No Lake Features Found! </h4>
                  <div class="mdl-card__media">
                    <img src="/assets/images/dog.png" width="220" height="140" border="0" alt="" style="padding:20px;">
                  </div>
                  <div class="mdl-card__supporting-text">
                    We did not find any lake features in the system. 
                    Please click on the create button to get started. 
                  </div>
                  <div class="mdl-card__actions mdl-card--border">
                    <button class="mdl-button mdl-js-button mdl-button--raised" ng-click="goto_create()">
                          Create a lake feature
                    </button>
                  </div>

                </div> <!-- no content card -->

                
                <div class="table-container" ng-show="display.table">

                    <div class="wide-mdl-card__top__action ">
                        <!-- FAB button with ripple -->
                        <button class="mdl-button mdl-js-button" ng-click="goto_create()">
                          <i class="material-icons">add</i>
                        </button>
                    </div>
                    
                     <div class="mdl-card mdl-shadow--4dp wide-mdl-card" ng-repeat="feature in features">
                        <h3 class="mdl-card__title" ng-bind="feature.name"></h3>
                        <div class="mdl-card__supporting-text">
                            <ul class="mdl-list">
                                <li class="mdl-list__item">
                                    <span class="mdl-list__item-primary-content">
                                        <i class="material-icons mdl-list__item-icon">all_out</i>
                                        {{feature.iocodeValue}} / {{feature.featureTypeValue}}
                                    </span>
                                    

                                </li>

                                <li class="mdl-list__item">
                                    <span class="mdl-list__item-primary-content">
                                        <i class="material-icons mdl-list__item-icon">place</i>
                                        {{feature.lat}},{{feature.lon}}
                                    </span>
                                    <span class="mdl-list__item-sub-title">Location</span>
                                </li>

                                <li class="mdl-list__item">
                                    <span class="mdl-list__item-primary-content">
                                        <i class="material-icons mdl-list__item-icon">border_outer</i>
                                        {{feature.width}},{{feature.maxHeight}}
                                    </span>
                                    <span class="mdl-list__item-sub-title">width/Height</span>
                                </li>

                
                                <li class="mdl-list__item">
                                    <span class="mdl-list__item-primary-content"> 
                                        <i class="material-icons mdl-list__item-icon">visibility</i>
                                    {{feature.monitoringValue}}
                                    
                                    </span>
                                    <span class="mdl-list__item-sub-title">
                                        Monitoring
                                    </span>
                                    
                                </li>
                            </ul>
                        </div>
                         <div class="mdl-card__actions mdl-card--border">
                            <button class="mdl-button mdl-js-button" ng-click="goto_edit(feature.id)"><i class="material-icons">edit</i></button>
                            &nbsp;
                            <button class="mdl-button mdl-js-button"><i class="material-icons">delete</i></button>
                         </div>
                     </div> <!-- card -->


                </div> <!-- display table container -->

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
    <script src="/assets/js/main.js?v=2"></script>

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