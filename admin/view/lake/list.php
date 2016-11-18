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

   <?php include (APP_WEB_DIR.'/inc/toolbar.inc'); ?>
    <?php include (APP_WEB_DIR.'/inc/drawer.inc'); ?>

      <main class="mdl-layout__content">
         <div class="page-content">

         <div class="pad-bottom"></div>
          <?php include (APP_WEB_DIR.'/inc/page_error.inc'); ?>
<!-- card -->
<div class="mdl-grid">
<div class="mdl-layout-spacer"></div>
	<div class="mdl-cell mdl-card mdl-cell--6-col mdl-shadow--4dp">
	<div class="mdl-card__title formcard mdl-color-text--white">
		<h2 class="mdl-card__title-text formcard mdl-color-text--indigo">Lakes</h2>
		<div class="mdl-layout-spacer"></div>
      <span class="mdl-color-text--indigo">Create</span>&nbsp;
		<h2 class="mdl-card__title-text formcard">
        
      <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect" ng-click="lake_create()"><i class="material-icons">add</i></button>
      </h2>
	</div>

   <div class="">
      <table class="mdl-data-table mdl-js-data-table">
         <thead>
            <tr></tr>
         </thead>
         <tbody class="tbl-row-text-font">
            <tr>
            <td class="mdl-data-table__cell--non-numeric">Lake1</td>
            <td>
               <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"><i class="material-icons">edit</i></button>
            </td>
            <td>
               <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"><i class="material-icons">delete</i></button>
            </td>
            </tr>
            <tr>
            <td class="mdl-data-table__cell--non-numeric">Lake2</td>
            <td>
               <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"><i class="material-icons">edit</i></button>
            </td>
            <td>
               <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"><i class="material-icons">delete</i></button>
            </td>
            </tr>
            <tr><td class="mdl-data-table__cell--non-numeric">Lake3</td>
            <td>
               <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"><i class="material-icons">edit</i></button>
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

         
          if($scope.debug) { 
            console.log("server response:: devices:%O", data); 
          }

          if (status != 200 || data.code != 200) {
            console.log(response);
            var error = data.error || (status + ":error Retrieving  Data from Server");
            $scope.showError(error);
            return;
          }

          var recordList = response.data.result || [] ;
          $scope.lakes=recordList;

          if($scope.debug) {
            console.log("records list from server",recordList);
          }

         

        },function(response) {
          $scope.processResponse(response);
        });

      };


      $scope.lake_create=function(){
      
      $window.location.href = "/admin/view/lake/create.php";

      };


   
      
      $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug ;
        $scope.base = $scope.gparams.base ;
        $scope.lakes =[];
        $scope.errorMessage = "";
        $scope.getList();


      });

       
        


    </script>
</body>
</html>