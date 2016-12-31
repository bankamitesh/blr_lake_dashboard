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
    <title> Lake water balance data upload page </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/mdl/material.min.css" />
    <link rel="stylesheet" href="/assets/mdl/material.light_green-pink.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css?v=3" />

</head>

<body  ng-controller="yuktix.admin.lake.wb.upload">

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
                        
                         <h5> Features </h5>
                         <div>
                            <select id="lake_feature"
                                    ng-model="selectedFeature"
                                    ng-change="select_feature(selectedFeature)"
                                    ng-options="feature.name for feature in features">
                            </select>
                        </div>

                       
                                <ul class="mdl-list">
                                    <li class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-icon">all_out</i>
                                            {{selectedFeature.iocodeValue}} / {{selectedFeature.featureTypeValue}}
                                        </span>
                                        

                                    </li>

                                    <li class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-icon">place</i>
                                            {{selectedFeature.lat}},{{selectedFeature.lon}}
                                        </span>
                                        <span class="mdl-list__item-sub-title">Location</span>
                                    </li>

                                    <li class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content"> 
                                            <i class="material-icons mdl-list__item-icon">visibility</i>
                                        {{selectedFeature.monitoringValue}}
                                        
                                        </span>
                                        <span class="mdl-list__item-sub-title">
                                            Monitoring
                                        </span>
                                        
                                    </li>
                                </ul>
                            


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

    yuktixApp.controller("yuktix.admin.lake.wb.upload", function ($scope, lake, fupload, feature,$window) {

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
                
                for( var i = 0 ; i < $scope.features.length; i++) {
                    var featureObj =  $scope.features[i] ;
                    lake.assignFeatureCodeValues($scope.codeMap, featureObj);
                    $scope.features[i] = featureObj ; 
                    if($scope.debug) {
                        console.log("feature : object with assigned code values ::%O", featureObj);
                    }
                }

                if($scope.features.length > 0) {
                    $scope.selectedFeature = $scope.features[0] ;
                }

                $scope.clearPageMessage();
                

            },function(response) {
                $scope.processResponse(response);
            });

        };

        $scope.select_feature = function(feature) {
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
       
        $scope.translate_code = function(feature) {

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

        // display data initialization
        $scope.display = {} ;
       
        // lake edit menu display 
        $scope.display.lakeEditMenu = {} ;
        $scope.display.lakeEditMenu.waterBalance = true ;

        // sample stage-volume data 
        $scope.samples = [] ;
        
        $scope.samples.push("0.15 , 1317.281689");
        $scope.samples.push("0.65 , 27859.87955");
        $scope.samples.push("1.15 , 97549.57163");
        $scope.samples.push("1.65 , 217217.1273");
        
        // start:
        $scope.init_codes() ;
    

    });
</script>




</html>