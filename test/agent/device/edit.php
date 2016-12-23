<?php  

  include ("lake-app.inc");
  include(APP_WEB_DIR.'/inc/header.inc');

  use \com\indigloo\Url ;

  $gparams = new \stdClass ;
  $gparams->debug = false ;
  $gparams->base = Url::base() ;

  $serialNumber = Url::tryQueryParam("serial_number");
  if(empty($serialNumber)) {
    echo "<h1> required parameter serial_number is missing </h1>" ;
    exit(1);
  }

  if(array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true ;
  }

  $gparams->serialNumber = $serialNumber ;

?>
<html ng-app="YuktixAgentApp">
 
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_green-amber.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css?v=1">
    
    <style>

     .channel-box { 
       width:160px; 
       border: 1px dashed #ccc;
        padding: 7px;
      }

    </style>

</head>

<body ng-controller="yuktix.agent.main">

     <div class="mdl-layout mdl-js-layout" id="container">

    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="docs-layout-content mdl-layout__content ">
      <?php include(APP_WEB_DIR . '/inc/ui/mdl-progress.inc'); ?>

        <div class="mdl-grid mdl-grid--no-spacing">

            <div class="mdl-cell mdl-cell--4-col mdl-cell--3-offset">
               <?php include(APP_WEB_DIR . '/inc/ui/mdl-page-message.inc'); ?>
               <form name="updateForm" >

                  <h5> {{device.serialNumber}} / edit </h5>
                  
                  <div class="mdl-textfield mdl-js-textfield">
                      <h6>Location</h6>
                      <input class="mdl-textfield__input" type="text" name="name" id="name" ng-model="device.location">   
                  </div>

                  <div class="mdl-textfield mdl-js-textfield">
                      <h6>Description</h6>
                      <input class="mdl-textfield__input" type="text" name="name" id="name" ng-model="device.description">
                  </div>
                  
                  <h5> Channels </h5> 
                  <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                      <thead>
                        <tr>
                          <th class="mdl-data-table__cell--non-numeric">&nbsp;</th>
                          <th class="mdl-data-table__cell--non-numeric">Name</th>
                          <th class="mdl-data-table__cell--non-numeric">Units</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <tr ng-repeat="channel in device.channels track by $index">
                          <td> <span ng-bind="channel.code"> </span>  </td>

                          <td>
                              <input class=" channel-box" type="text" name="{{channel.name}}" ng-model="channel.name">
                          </td>
                          <td>
                              <input class="channel-box" type="text" name="{{channel.units}}" ng-model="channel.units">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  
                  <div class="form-button-container">
                      <button class="mdl-button mdl-js-button mdl-button--raised"ng-click="update_device()" type="submit">
                          Save device information 
                      </button>
                  </div>
      
              </form>
            </div>
               
        </div> <!-- grid -->
    </main>
    
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-footer.inc'); ?>

</div> <!-- container -->
</body>

    <script src="/assets/js/material.min.js"></script>
    <script src="/assets/js/angular.min.js"></script>
    <script src="/assets/js/agent.js"></script>

    <script>
      
      agentApp.controller("yuktix.agent.main",function($scope,agent,$window) {
    
        $scope.getDevice = function() {
            
          $scope.showProgress("getting device from the server...");
          agent.getDevice($scope.base,$scope.debug, $scope.device.serialNumber) .then( function(response) {

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

            $scope.device = data.result ;
            $scope.clearPageMessage();

        },function(response) {
          $scope.processResponse(response);
        });

      };

      $scope.update_device = function() {

            var errorObject = $scope.updateForm.$error;
            if ($scope.validateForm(errorObject)) {
                return;
            }

            $scope.showProgress("submitting device data to server");
            if ($scope.debug) {
                console.log("form values %O ", $scope.device);
            }

            agent.updateDevice($scope.base, $scope.debug, $scope.device).then(function (response) {

                    var status = response.status || 500;
                    var data = response.data || {};

                    if ($scope.debug) {
                        console.log("API response :");
                        console.log(data);
                    }

                    if (status != 200 || data.code != 200) {
                        console.log("browser response object: %o" ,response);
                        var error = data.error || (status + ":error submitting device update form");
                        $scope.showError(error);
                        return;
                    }

                    // goto index page 
                    $window.location.href = "/test/agent/index.php" ;
                    

                }, function (response) {
                    $scope.processResponse(response);
                });

      }

      // set page parameters
      $scope.gparams = <?php echo json_encode($gparams); ?> ;
      $scope.debug = $scope.gparams.debug ;
      $scope.base = $scope.gparams.base ;

      // init data 
     
      $scope.device = {} ;
      $scope.device.serialNumber = $scope.gparams.serialNumber ;
      $scope.errorMessage = "";

      // init display: table or no table
      $scope.display = {} ;
      
      // start 
      $scope.getDevice();

    });


  </script>


</html>