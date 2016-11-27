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
      <link rel="stylesheet" href="/assets/css/material.min.css">
      <link rel="stylesheet" href="/assets/css/main.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
   </head>
<body ng-controller="yuktix.admin.lake.list">
   <!-- Always shows a header, even in smaller screens. -->
   <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

       <header class="mdl-layout__header">
           <div class="mdl-layout__header-row">
               <?php include (APP_WEB_DIR.'/inc/title.inc'); ?>
               <?php include (APP_WEB_DIR.'/inc/logout_menu_bar.inc'); ?>
           </div>
       </header>

      <main class="mdl-layout__content">
         <div class="page-content">

         <div class="pad-bottom"></div>

          <?php include (APP_WEB_DIR.'/inc/page_error.inc'); ?>
<!-- card -->
<div class="mdl-grid">
<div class="mdl-layout-spacer"></div>
	<div class="mdl-cell mdl-card mdl-cell--12-col mdl-shadow--4dp">
	<div class="mdl-card__title formcard mdl-color-text--white">
		<h2 class="mdl-card__title-text formcard mdl-color-text--indigo">Lakes</h2>
		<div class="mdl-layout-spacer"></div>
      <span class="mdl-color-text--indigo"></span>&nbsp;
		<h2 class="mdl-card__title-text formcard">
        
      <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect" ng-click="lake_create()"><i class="material-icons">add</i></button>
      </h2>
	</div>

   <div class="">

      <table class="mdl-data-table mdl-js-data-table">
         <thead>
         <tr class="tbl-row-text-font">
             <th>NAME</th>
             <th>ABOUT</th>
             <th>LATTITUDE</th>
             <th>LONGTITUDE</th>
             <th>ADDRESS</th>
             <th>MAX AREA</th>
             <th>MAX VOLUME</th>
             <th>RECHARGE RATE</th>
             <th>TYPE CODE</th>
             <th>AGENCY CODE</th>
             <th>USAGE CODE</th>
         </tr>
         </thead>
         <tbody  ng-repeat="lake in lakes">
            <tr>
            <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.name"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.about"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.lat"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.lon"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.address"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.maxArea"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.maxVolume"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.rechargeRate"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.typeCode"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.agencyCode"></td>
                <td class="mdl-data-table__cell--non-numeric" ng-bind="lake.usageCode"></td>
            <td>
               <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" ng-click="lake_edit(lake.id)"><i class="material-icons">edit</i></button>
            </td>
            <td>
               <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"><i class="material-icons">delete</i></button>
            </td>
            </tr>


         </tbody>
      </table>  
   </div>
	</div>
	<div class="mdl-layout-spacer"></div>
</div> 
<!-- end card -->        


         </div>
          <?php include (APP_WEB_DIR.'/inc/footer.inc'); ?>
      </main>
   </div>
    <script src="/assets/js/material.min.js"></script>
    <script src="/assets/js/angular.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script>
      
      yuktixApp.controller("yuktix.admin.lake.list",function($scope,lake,$window) {

           
      $scope.getList = function() {
             

        $scope.showProgress("Getting data from Server...");
             

        // contact user factory
        lake.list($scope.base,$scope.debug)
        .then( function(response) {

          var status = response.status || 500;
          var data = response.data || {};

            $scope.lakes = data.result ;

          if($scope.debug) { 
            console.log("server response:: devices:%O", data);
          }

          if (status != 200 || data.code != 200) {
            console.log(response);
            var error = data.error || (status + ":error Retrieving  Data from Server");
            $scope.showError(error);
            return;
          }


          if($scope.debug) {
            console.log("records list from server",data);
          }



         

        },function(response) {
          $scope.processResponse(response);
        });

      };


      $scope.lake_create=function(){
      
      $window.location.href = "/admin/view/lake/create.php";

      };

          $scope.lake_edit=function(lakeId){

              if($scope.debug){
                  console.log("lake row clicked: id is:" + lakeId);
              }

              //$window.location.href = "/admin/view/lake/edit.php?id="+lakeId;

          };



          $scope.lakes = {} ;
        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug ;
        $scope.base = $scope.gparams.base ;
        $scope.errorMessage = "";
        $scope.getList();



      });


    </script>
</body>
</html>