<?php  

  include ("lake-app.inc");
  include(APP_WEB_DIR.'/inc/header.inc');

  use \com\indigloo\Url ;

  $gparams = new \stdClass ;
  $gparams->debug = false ;
  $gparams->base = Url::base() ;

  if(array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true ;
  }

?>
<html ng-app="YuktixApp">
 
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/mdl/material.min.css">
    <link rel="stylesheet" href="/assets/mdl/material.light_green-pink.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css">
  
</head>

<body ng-controller="yuktix.admin.lake.list">

  <div class="mdl-layout mdl-js-layout" id="container">

    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="docs-layout-content mdl-layout__content ">
      <?php include(APP_WEB_DIR . '/inc/ui/mdl-progress.inc'); ?>

      <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--3-col"> </div>
        <div class="mdl-cell mdl-cell--9-col container-810"> 
           <?php include(APP_WEB_DIR . '/inc/ui/mdl-page-message.inc'); ?>
             
             <div class="mdl-card mdl-shadow--4dp no-table-card" ng-show="display.notable">
                 <h4 class="mdl-card__title"> No Lakes Found! </h4>
                  <div class="mdl-card__media">
                    <img src="/assets/images/dog.png" width="220" height="140" border="0" alt="" style="padding:20px;">
                  </div>
                  <div class="mdl-card__supporting-text">
                    We did not find any lakes in the system. Please click on the 
                    button below to get started. 
                  </div>
                  <div class="mdl-card__actions mdl-card--border">
                    <button class="mdl-button mdl-js-button mdl-button--raised" ng-click="goto_create()">
                          Create a lake
                    </button>
                  </div>

                </div> <!-- no content card -->

                <div class="table-container" ng-show="display.table">

                    <div class="wide-mdl-card__top__action ">
                        
                        <button class="mdl-button mdl-js-button mdl-button--raised" ng-click="goto_create()">
                          <i class="material-icons">add</i>
                        </button>
                    </div> <!-- top:action -->

                    
                    <div   ng-repeat="lake in lakes" class="mdl-card mdl-shadow--4dp wide-mdl-card">
                        <h3 class="mdl-card__title" ng-bind="lake.name"></h3>
                        <div class="mdl-card__supporting-text">
                         
                            <ul class="mdl-list">
                              <li class="mdl-list__item"> 
                                <span class="mdl-list__item-primary-content">
                                  <i class="material-icons mdl-list__item-icon">place</i>
                                    {{lake.lat}},{{lake.lon}}
                                  </span>
                                  <span class="mdl-list__item-sub-title">Location</span>
                              </li>
                              <li class="mdl-list__item"> 
                                <span class="mdl-list__item-primary-content">
                                  <i class="material-icons mdl-list__item-icon">border_outer</i>
                                    {{lake.maxArea}},{{lake.maxVolume}}
                                  </span>
                                  <span class="mdl-list__item-sub-title">Area/Volume</span>
                              </li>
                              
                              <li class="mdl-list__item"> 
                                <span class="mdl-list__item-primary-content">
                                  <i class="material-icons mdl-list__item-icon">airport_shuttle</i>
                                    {{lake.address}} 
                                  </span>
                                  <span class="mdl-list__item-sub-title">Address</span>

                              </li>
                            </ul>

                            <p ng-bind="lake.about"> </p>

                        </div> 

                        <div class="mdl-card__actions mdl-card--border">
                          <button class="mdl-button mdl-js-button" ng-click="goto_edit(lake.id)"><i class="material-icons">edit</i></button>
                            &nbsp;
                            <button class="mdl-button mdl-js-button"><i class="material-icons">delete</i></button>
                            
                        </div>

                    </div> <!-- card --> 

                </div> <!-- display table: container -->
                
          </div>
      </div> <!-- grid:1 --> 


      <div class="mdl-grid mdl-grid--no-spacing">
        <div class="mdl-cell mdl-cell--12-col">
           
          <?php include(APP_WEB_DIR . '/inc/ui/mdl-footer.inc'); ?>
        </div>
		 </div> <!-- grid:footer -->

    </main>
    
  
  </div> <!-- container -->
</body>

    <script src="/assets/mdl/material.min.js"></script>
    <script src="/assets/js/angular.min.js"></script>
    <script src="/assets/js/main.js"></script>

    <script>
      
      yuktixApp.controller("yuktix.admin.lake.list",function($scope,lake,$window) {
    
        $scope.getLakes = function() {
            
          $scope.showProgress("Getting lakes data from the server...");
          // contact lake factory
          lake.list($scope.base,$scope.debug) .then( function(response) {

            var status = response.status || 500;
            var data = response.data || {};
            

            if($scope.debug) { 
              console.log("server response:: lakes data:%O", data);
            }

            if (status != 200 || data.code != 200) {
              console.log(response);
              var error = data.error || (status + ":error Retrieving  Data from Server");
              $scope.showError(error);
              return;

            }

            if($scope.debug) {
              console.log("data fetched from server",data);
            }

            // assign to lakes in scope
            $scope.lakes = data.result ;
            if($scope.lakes.length > 0 ) {
              $scope.display.notable = false ;
              $scope.display.table = true ;
            } else {
              $scope.display.notable = true ;
              $scope.display.table = false ;
            }

            $scope.clearPageMessage();

        },function(response) {
          $scope.processResponse(response);
        });

      };


      $scope.goto_create = function() {
        $window.location.href = "/admin/view/lake/create.php";
      };

      $scope.goto_edit=function(lakeId){
        if($scope.debug){
            console.log("lake row clicked: id ::" + lakeId);
        }
        $window.location.href = "/admin/view/lake/edit.php?lake_id="+lakeId;

      };

      // set page parameters
      $scope.gparams = <?php echo json_encode($gparams); ?> ;
      $scope.debug = $scope.gparams.debug ;
      $scope.base = $scope.gparams.base ;

      // init data 
      $scope.lakes = {} ;
     
      $scope.errorMessage = "";

      // init display: table or no table
      $scope.display = {} ;
      $scope.display.notable = false ;
      $scope.display.table = false ;

      $scope.getLakes();

    });


  </script>


</html>