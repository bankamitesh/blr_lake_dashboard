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
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-blue.min.css" />
    <link rel="stylesheet" href="/assets/d3/rickshaw.css">
    <link rel="stylesheet" href="/assets/css/main.css?v=1">
    
     <style>

            /* override rickshaw 3D css */

            .graph-container {
                margin-top:20px;
                margin-bottom :20px;
                padding:20px; 
                font-size : 12px;
                float:left ;
                position: relative ;
            } 

            .graph {
                display: inline-block ;
            }

            .rickshaw_graph {
                position:relative ;
                display: inline-block ;
            }

            .rickshaw_graph .x_tick .title {
                opacity: 1 !important;
                position: absolute !important;
                color: #646464;
                bottom: 1px;
                /* margin-top:10px; */
            }
                
            .rickshaw_graph .y_ticks text {
                opacity: 1 !important;
                position: relative !important;
            }

            .rickshaw_graph .detail .x_label { display: none }
            .rickshaw_graph .detail .item { line-height: 1.4; padding: 0.5em }
            .detail_swatch { display: inline-block; width: 10px; height: 10px; margin: 0 4px 0 0 }
            .rickshaw_graph .detail .date { color: #a0a0a0 }



        </style>

</head>

<body ng-controller="yuktix.agent.chart">

     <div class="mdl-layout mdl-js-layout" id="container">

    <?php include(APP_WEB_DIR . '/inc/ui/mdl-header.inc'); ?>
    <?php include(APP_WEB_DIR . '/inc/ui/mdl-drawer.inc'); ?>
   
    <main class="docs-layout-content mdl-layout__content ">
      <?php include(APP_WEB_DIR . '/inc/ui/mdl-progress.inc'); ?>
      <?php include(APP_WEB_DIR . '/inc/ui/mdl-page-message.inc'); ?>

         <div class="mdl-grid" ng-repeat="plot in plots">
            <div  class="mdl-cell mdl-cell--9-col">
                
                
                <h6> {{plot.name}}  ({{plot.current_value}} {{plot.units}}) <span ng-bind="plot.at_human"></span> </h6>
                <div class="graph-container">
                    <div class="graph">
                        
                        <rickshaw-chart  data="plot" />
                    </div>
                </div> <!-- graph -->
                

            </div>
        </div> <!-- grid --> 
    </main>
    
    

</div> <!-- container -->
</body>

    <script src="/assets/js/material.min.js"></script>
    <script src="/assets/js/angular.min.js"></script>
    <script src="/assets/d3/d3.v2.js"></script>
	  <script src="/assets/d3/rickshaw.js"></script>
    <script src="/assets/js/agent.js?v=2"></script>

    <script>
      
      agentApp.controller("yuktix.agent.chart",function($scope,agent,$window) {
    
          $scope.drawCharts = function(data) {
                
              // for details: see plot.html sample
              var i = 0 ;
              for (i = 0 ; i < data.length ; i++) {

                  plot = data[i] ;
                  plot.interpolation = "step-after" ;
                  plot.width = 600 ;
                  plot.height = 150 ;

                  // add UI properties 
                  plot.renderer = "line" ;
                  plot.tick = "15minute";
                  plot.color = "#FF5733" ;
                  plot.at_human = new Date(plot.unix_ts * 1000).toLocaleString() ;
                  $scope.plots.push(plot) ;
                  
              }

          };
    	
          $scope.getPlotData = function(serialNumber) {
            
              $scope.showProgress("getting plot data  from the server...");
              agent.getPlotData($scope.base,$scope.debug, serialNumber).then( function(response) {

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

                $scope.clearPageMessage();
                $scope.drawCharts(data.result);


            },function(response) {
              $scope.processResponse(response);
            });

        };


        // initialize page data 
        // set page parameters
        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug ;
        $scope.base = $scope.gparams.base ;


        // init data 
        $scope.plots = [] ;
        $scope.serialNumber = $scope.gparams.serialNumber ;
        $scope.getPlotData($scope.serialNumber) ;


    });


  </script>


</html>