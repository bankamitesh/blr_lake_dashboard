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
<!DOCTYPE html>
<html ng-app="YuktixAgentApp">
 
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_green-amber.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css?v=1">
    
</head>

<body ng-controller="yuktix.agent.main">

     <div class="mdl-layout mdl-js-layout" id="container">

    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="docs-layout-content mdl-layout__content ">
      <?php include(APP_WEB_DIR . '/inc/ui/mdl-progress.inc'); ?>
      <?php include(APP_WEB_DIR . '/inc/ui/mdl-page-message.inc'); ?>
        <div class="mdl-grid">
           <div class="mdl-card mdl-shadow--4dp no-table-card" ng-show="display.notable">
                 <h4 class="mdl-card__title"> No Devices Found! </h4>
                  <div class="mdl-card__media">
                    <img src="/assets/images/dog.png" width="220" height="140" border="0" alt="" style="padding:20px;">
                  </div>
                  <div class="mdl-card__supporting-text">
                    We did not find any devices in the system.
                  </div>
                  
                </div> <!-- no table mdl card -->
        </div> <!-- grid:1 --> 

        <div class="mdl-grid mdl-grid--no-spacing" ng-show="display.table">
            <div  class="mdl-cell mdl-cell--4-col" ng-repeat="device in devices">
                <div   class="mdl-card mdl-shadow--4dp">
                     <h6 class="mdl-card__title" ng-bind="device.serialNumber"></h6>
                      <div class="mdl-card__supporting-text">
                          <div> 
                            <ul class="mdl-list">
                              <li class="mdl-list__item"> 
                                <span class="mdl-list__item-primary-content">
                                  <i class="material-icons mdl-list__item-icon">place</i>
                                    {{device.location}}
                                  </span>
                                  <span class="mdl-list__item-sub-title">Location</span>
                              </li>

                              <li class="mdl-list__item"> 
                                <span class="mdl-list__item-primary-content">
                                    {{device.description}}
                                  </span>
                                  
                              </li>
                             
                              <li class="mdl-list__item" ng-repeat="channel in device.channels"> 
                                <span class="mdl-list__item-primary-content">
                                  <i class="material-icons mdl-list__item-icon">{{channel.icon}}</i>
                                    {{channel.value}}  {{channel.units}}
                                  </span>
                                  <span class="mdl-list__item-sub-title">{{channel.name}}</span>

                              </li>

                            </ul>
                          </div>
                         
                      </div>

                      <div class="mdl-card__actions mdl-card--border">
                        <button class="mdl-button mdl-js-button" ng-click="goto_edit(device.serialNumber)"><i class="material-icons">edit</i></button>
                         <button class="mdl-button mdl-js-button" ng-click="goto_chart(device.serialNumber)"><i class="material-icons">show_chart</i></button>
                        
                      </div>
                    </div> <!-- card -->

            </div>

        </div> <!-- grid:2 -->
        

    </main>
    
    

</div> <!-- container -->
</body>

    <script src="/assets/js/material.min.js"></script>
    <script src="/assets/js/angular.min.js"></script>
    <script src="/assets/js/agent.js"></script>

    <script>
      
      agentApp.controller("yuktix.agent.main",function($scope,agent,$window) {
    
        $scope.getDevices = function() {
            
          $scope.showProgress("getting devices from the server...");
          agent.getDevices($scope.base,$scope.debug) .then( function(response) {

            var status = response.status || 500;
            var data = response.data || {};
            
            if($scope.debug) { 
              console.log("server response:: data:%O", data);
            }

            if (status != 200 || data.code != 200) {
              console.log(response);
              var error = data.error || (status + ":error retrieving  data from server");
              $scope.showError(error);
              return;

            }

            // bind data to scope 
            $scope.devices = data.result ;
            
            if($scope.devices.length > 0 ) {
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

      $scope.goto_edit = function(serialNumber) {
        $window.location.href = "/test/agent/device/edit.php?serial_number=" + serialNumber;
      }

      $scope.goto_chart = function(serialNumber) {
        $window.location.href = "/test/agent/chart.php?serial_number=" + serialNumber;
      }

      // set page parameters
      $scope.gparams = <?php echo json_encode($gparams); ?> ;
      $scope.debug = $scope.gparams.debug ;
      $scope.base = $scope.gparams.base ;

      // init data 
      $scope.devices = {} ;
      $scope.errorMessage = "";

      // init display: table or no table
      $scope.display = {} ;
      $scope.display.notable = false ;
      $scope.display.table = false ;

      // start 
      $scope.getDevices();

    });


  </script>


</html>