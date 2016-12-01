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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_green-amber.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css">
    
</head>

<body ng-controller="yuktix.admin.lake.list">

     <div class="mdl-layout mdl-js-layout" id="container">

    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="docs-layout-content mdl-layout__content ">
        <div class="content mdl-grid mdl-grid--no-spacing" id="content">
            <div class="mdl-cell mdl-cell--9-col mdl-cell--3-offset">
               <?php include(APP_WEB_DIR . '/inc/ui/page-error.inc'); ?>

               <div class=" table-container">
                 <div class="mdl-grid">
                   <div class="mdl-cell mdl-cell--6-col">
                      <h5> Lakes </h5>
                    </div>
                    <div class="mdl-cell mdl-cell--3-col">
                      <!-- FAB button with ripple -->
                      <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored mdl-js-ripple-effect" ng-click="goto_create()">
                        <i class="material-icons">add</i>
                      </button>
                    </div>

                  </div>

                </div>

                 <table class="mdl-data-table mdl-js-data-table">
                  <thead>
                    <tr>
                      <th class="mdl-data-table__cell--non-numeric">Name</th>
                      <th class="mdl-data-table__cell--non-numeric">Location</th>
                      <th class="mdl-data-table__cell--non-numeric">&nbsp;</th>
                      <th class="mdl-data-table__cell--non-numeric">Area</th>
                      <th class="mdl-data-table__cell--non-numeric">Volume</th>
                      <th class="mdl-data-table__cell--non-numeric">&nbsp;</th>
                      <th class="mdl-data-table__cell--non-numeric">&nbsp;</th>

                    </tr>
                  </thead>
                  <tbody>
                    <tr ng-repeat="lake in lakes">
                        <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.name"></td>
                        <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.lat"></td>
                        <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.lon"></td>    
                        <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.maxArea"></td>
                        <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.maxVolume"></td>
                        
                        <td>
                          <button class="mdl-button mdl-js-button" ng-click="goto_edit(lake.id)"><i class="material-icons">edit</i></button>
                        </td>
                        <td>
                          <button class="mdl-button mdl-js-button"><i class="material-icons">delete</i></button>
                          </td>
                    </tr>

                  </tbody>
                </table>
                </div>

                

            </div> <!-- grid -->
        
    </main>
    
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-footer.inc'); ?>

</div> <!-- container div -->
</body>

    <script src="/assets/js/material.min.js"></script>
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
        $window.location.href = "/admin/view/lake/edit.php?id="+lakeId;

      };



      $scope.lakes = {} ;
      $scope.gparams = <?php echo json_encode($gparams); ?> ;
      $scope.debug = $scope.gparams.debug ;
      $scope.base = $scope.gparams.base ;
      $scope.errorMessage = "";
      $scope.getLakes();

    });


  </script>


</html>