<?php

include("lake-app.inc");
include(APP_WEB_DIR . '/inc/header.inc');

use \com\indigloo\Url;
use \com\yuktix\lake\auth\Login as Login ;

// role check
// redirect to login page
Login::isCustomerAdmin("/admin/login.php") ;

$gparams = new \stdClass;
$gparams->debug = false;
$gparams->base = Url::base();

if (array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true;
}

?>
<html  ng-app="YuktixApp">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_green-amber.min.css" />
   

   <!--

    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.pink-deep_purple.min.css" />
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_blue-red.min.css" />
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.light_green-amber.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css?version=1">
    -->
    
    <style>
        .lake-edit-links {
            height: 100% ;
        }
        .lake-edit-links a {
            text-decoration: none ;
            color: black ;
        }

        select {
            width:310px;
            height:28px; 
            font-size: 16px; 
        }

    </style>
    
</head>
<body  ng-controller="yuktix.admin.lake.edit">

<div class="mdl-layout mdl-js-layout container">

    <header class="mdl-layout__header">
        <div class="mdl-layout-icon"></div>
        <div class="mdl-layout__header-row">
            <span class="mdl-layout__title">Bangalore Lake Dashboard</span>
            <div class="mdl-layout-spacer"></div>
            <div>
                <button id="site-toolbar-account" class="mdl-button mdl-js-button mdl-button--icon">
                    <i class="material-icons">account_circle</i>
                </button>

                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="site-toolbar-account">
                    <li class="mdl-menu__item">Some Action</li>
                    <li class="mdl-menu__item">Another Action</li>
                    <li disabled class="mdl-menu__item">Disabled Action</li>
                    <li class="mdl-menu__item">Yet Another Action</li>
                </ul>
            </div>

        </div>
    </header>
    <div class="mdl-layout__drawer">
        <span class="mdl-layout__title">&nbsp;</span>
        <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="#">Nav link 1</a>
            <a class="mdl-navigation__link" href="#">Nav link 2</a>
            <a class="mdl-navigation__link" href="#">Nav link 3</a>
        </nav>
        
    </div>

    <main class="docs-layout-content mdl-layout__content mdl-color-text--grey-600">
        <div class="content mdl-grid mdl-grid--no-spacing" id="content">
            <div class="mdl-components mdl-js-components mdl-cell mdl-components__nav docs-text-styling mdl-shadow--4dp mdl-cell--3-col">

                <ul class="lake-edit-links mdl-list ">
                    
                        <li class="mdl-list__item">
                           <a href="#" class="mdl-components__link mdl-component"> 
                               <span class="mdl-list__item-primary-content">General information</span> 
                            </a>
                        </li>
                        
                        <li class="mdl-list__item">
                           <a href="#" class="mdl-components__link mdl-component"> 
                               <span class="mdl-list__item-primary-content">Bathymetry</span> 
                            </a>
                        </li>

                        <li class="mdl-list__item">
                           <a href="#" class="mdl-components__link mdl-component"> 
                               <span class="mdl-list__item-primary-content">Stage Volume information</span> 
                            </a>
                        </li>

                        <li class="mdl-list__item">
                           <a href="#" class="mdl-components__link mdl-component"> 
                               <span class="mdl-list__item-primary-content">Zones</span> 
                            </a>
                        </li>

                        <li class="mdl-list__item">
                           <a href="#" class="mdl-components__link mdl-component"> 
                               <span class="mdl-list__item-primary-content">Inlet/Outlets</span> 
                            </a>
                        </li>
                </ul>
            </div> <!-- grid:3 -->
            <div class="mdl-cell mdl-cell--7-col mdl-cell--1-offset">

                <form name="createForm">
                            <h4>Edit Lake </h4>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" name="name" id="name"
                                       ng-model="lakeObj.name" required>
                                <label class="mdl-textfield__label" for="name">Lake Name </label>
                            </div>
                            <br>
                            
                              <div class="mdl-textfield mdl-js-textfield">
                                <textarea class="mdl-textfield__input" type="text" rows="5" id="about" name="about"
                                          ng-model="lakeObj.about" required></textarea>
                                <label class="mdl-textfield__label" for="about">About...</label>
                            </div>
                            <br>

                            <div> <span> Lake Type </span> </div>

                            <div>
                                <select id="lake_type_select"
                                        ng-model="selectedLakeType"
                                        ng-change="select_lake_type(selectedLakeType)"
                                        ng-options="lakeType.value for lakeType in allLakeTypes">
                                </select>
                             
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="lat" name="latitude"
                                        ng-model="lakeObj.lat" required>
                                <label class="mdl-textfield__label" for="lat">Latitude...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="lon" name="longtitude"
                                       ng-model="lakeObj.lon" required>
                                <label class="mdl-textfield__label" for="lon">Longtitude...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield">
                                <textarea class="mdl-textfield__input" type="text" rows="3" id="address" name="address"
                                          ng-model="lakeObj.address" required></textarea>
                                <label class="mdl-textfield__label" for="address">Address...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="area" name="maxArea"
                                       ng-model="lakeObj.maxArea" required>
                                <label class="mdl-textfield__label" for="area">Max Area...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="volume" name="maxVolume"
                                       ng-model="lakeObj.maxVolume" required>
                                <label class="mdl-textfield__label" for="volume">Max Volume...</label>
                            </div>
                            <br>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="recharge_rate" name="rechargeRate"
                                       ng-model="lakeObj.rechargeRate" required>
                                <label class="mdl-textfield__label" for="recharge_rate">Rechange Rate...</label>
                            </div>
                            <br>

                            <div> <span> Agency</span> </div>
                            <div>
                                <select id="agency_select" name="agency"
                                        ng-model="selectedAgency"
                                        ng-change="select_agency(selectedAgency)"
                                        ng-options="agency.value for agency in allLakeAgencies"
                                        required>
                                </select>
                            </div>
                            <br>

                            <div> <span> Usage</span> </div>
                            <br>
                            <div class="mdl-grid mdl-grid--no-spacing">

                                <div class="mdl-cell mdl-cell--3-col" ng-repeat="usage in allLakeUsages">
                                    <label class="mdl-checkbox mdl-js-checkbox" for="{{usage.id}}">
                                        <input
                                            type="checkbox"
                                            id="{{usage.id}}" class="mdl-checkbox__input"
                                            ng-checked="lakeObj.usageCode.indexOf(usage.id) > -1"
                                            ng-click="toggle_usage_code(usage.id)"
                                            value="{usage.value}"
                                            name="usageCode" required>

                                        <span class="mdl-checkbox__label" ng-bind="usage.value"></span>
                                    </label>
                                </div>

                            </div>
                            <br>

                           

                        </form> 

            </div>



        </div>


    </main>
    <footer class="mdl-mega-footer">
        <div class="mdl-mega-footer__top-section">
            <div class="mdl-mega-footer__left-section">
                <button class="mdl-mega-footer__social-btn"></button>
                <button class="mdl-mega-footer__social-btn"></button>
                <button class="mdl-mega-footer__social-btn"></button>
            </div>
            <div class="mdl-mega-footer__right-section">
                <a href="">Link 1</a>
                <a href="">Link 2</a>
                <a href="">Link 3</a>
            </div>
        </div>
    </footer>

</div> <!-- container div -->



<script src="/assets/js/material.min.js"></script>
<script src="/assets/js/mdl-selectfield.min.js"></script>
<script src="/assets/js/angular.min.js"></script>
<script src="/assets/js/main.js"></script>


<script>

    yuktixApp.controller("yuktix.admin.lake.edit", function ($scope, lake, $window) {


        $scope.initCodes = function() {


            $scope.showProgress("Getting data from Server...");


            // contact user factory
            lake.getCodes($scope.base,$scope.debug)
                .then( function(response) {

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

                    // @todo : check for property names
                    // before doing data binding
                    $scope.allLakeAgencies = data.result.lakeAgencies ;
                    $scope.allLakeTypes = data.result.lakeTypes ;
                    $scope.allLakeUsages = data.result.lakeUsages ;

                    // @todo check array length before data binding
                    $scope.selectedAgency = $scope.allLakeAgencies[0] ;
                    $scope.lakeObj.agencyCode = $scope.selectedAgency.id ;

                    $scope.selectedLakeType = $scope.allLakeTypes[0] ;
                    $scope.lakeObj.typeCode = $scope.selectedLakeType.id ;

                    $scope.clearPageMessage();

                },function(response) {
                    $scope.processResponse(response);
                });

        };


        //factory for submitting form data





        $scope.select_agency = function(agency) {

            $scope.lakeObj.agencyCode = agency.id ;
            $scope.selectedAgency = agency ;

        } ;

        $scope.select_lake_type = function(lakeType) {

            $scope.lakeObj.typeCode = lakeType.id ;
            $scope.selectedLakeType = lakeType ;

        } ;

        $scope.toggle_usage_code = function(code) {

            var idx = $scope.lakeObj.usageCode.indexOf(code);

            if (idx > -1) {
                // already selected: turn off
                $scope.lakeObj.usageCode.splice(idx, 1);
            } else {
                // new selection
                $scope.lakeObj.usageCode.push(code);
            }

        };

        $scope.logout=function () {

            $window.location.href = "/admin/logout.php";

        };

        $scope.errorMessage = "";
        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = $scope.gparams.debug;
        $scope.base = $scope.gparams.base;

        //data initialization
        $scope.lakeObj = {};
        $scope.lakeObj.usageCode = [] ;
        $scope.allLakeAgencies = [] ;
        $scope.allLakeTypes = [] ;
        $scope.allLakeUsages = [] ;


        $scope.lakeCodes= {};
        $scope.initCodes();





    });
</script>
</body>
</html>