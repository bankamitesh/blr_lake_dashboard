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
    <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.css">
    <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.css" />
    <link rel="stylesheet" href="/test/bootstrap/assets/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="/test/bootstrap/assets/css/style.css" />
    <link rel="stylesheet" href="/assets/css/main.css">
    
  </head>

  <body ng-controller="yuktix.admin.lake.list">

    <div>

      <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-header.inc'); ?>
    
      <main>
        <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-progress.inc'); ?>

        <div class="container">
          <div class="row" style="padding:50px">
            <div  class="col-md-3"></div>
            <div  class="col-md-9">
              <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-page-message.inc'); ?>
                
              <div class="card" style="width: 40rem; border-color: black;" ng-show="display.notable">
                <img class="card-img-top" src="/assets/images/dog.png" style="background-color: pink">
                <div class="card-block">
                  <h4 class="card-title">No Lake Found</h4>
                  <p class="card-text"> 
                    We did not find any lake in the system.
                    </br> 
                    Please click on the create button to get started.
                  </p>
                  <button ng-click="goto_create()" class="btn btn-primary">
                    Create Lake
                  </button>
                </div>
              </div>
                
              <div class="table-container" ng-show="display.table">
                <div ng-repeat="lake in lakes" class="card" style="width: 40rem; border-color: black;">
                  <div class="card-block"> 
                    <div class="card-header">
                      <div class='btn-toolbar pull-right'>
                        <div class='btn-group'>
                          <button class="glyphicon glyphicon-plus" ng-click="goto_create()"></button>
                        </div>
                      </div>
                    </div> 
                    <h3 class="card-title" ng-bind="lake.name"></h3>
                    <div class="card--text" >
                      <ul class="list-group">
                          <li class="list-group-item">
                            <span class="glyphicon glyphicon-map-marker">
                              <font face="Times New Roman">{{lake.lat}}, {{lake.lon}}</font>
                            </span>
                            <span class="pull-right"><b>Location</b></span>
                          </li>
                          <li class="list-group-item"> 
                            <span class="glyphicon glyphicon-tint">
                              <font face="Times New Roman">{{lake.maxArea}},{{lake.maxVolume}}</font>
                            </span> 
                            <span class="pull-right"><b>Area/Volume</b></span>
                          </li>
                          <li class="list-group-item"> 
                            <span class="glyphicon glyphicon-home">
                              <font face="Times New Roman">{{lake.address}}</font>
                            </span>
                            <span class="pull-right"><b>Address</b></span>
                          </li>
                      </ul>
                      <font face="Times New Roman">
                        <p ng-bind="lake.about"></p>
                      </font>
                    </div>
                    <div class="card-footer">
                      <button class="glyphicon glyphicon-pencil" ng-click="goto_edit(lake.id)"></button>
                      &nbsp;
                      <button class="glyphicon glyphicon-trash"></button>
                    </div>
                  </div> 
                </div> <!-- card --> 
              </div> <!-- display table: container -->                  
            </div>
          </div>
        </div> <!-- grid:1 --> 
        
        <div class="row">
          <div class="col-md-12">
            <?php include(APP_WEB_DIR . '/inc/ui/bootstrap-footer.inc'); ?>
          </div>
        </div> <!-- grid:footer -->

      </main>
    </div> <!-- container -->
  </body>

  <script src="/assets/js/jquery-2.1.1.min.js"></script>
  <script src="/test/bootstrap/assets/js/bootstrap.js"></script>
  <script src="/test/bootstrap/assets/js/npm.js"></script>
  <script src="/test/bootstrap/assets/js/bootstrap.min.js"></script>
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
        $window.location.href = "/admin/lake/create.php";
      };

      $scope.goto_edit=function(lakeId){
        if($scope.debug){
            console.log("lake row clicked: id ::" + lakeId);
        }
          
        $window.location.href = "/admin/lake/edit.php?lake_id="+lakeId;

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