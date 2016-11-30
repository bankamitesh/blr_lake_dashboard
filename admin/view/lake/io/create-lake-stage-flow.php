<?php
include ("lake-app.inc");
include(APP_WEB_DIR.'/inc/header.inc');

use \com\indigloo\Url ;
use \com\yuktix\lake\auth\Login as Login ;

// already have login?
// do not redirect from login page.
// we redirect to login page for missing roles as well.

$gparams = new \stdClass ;
$gparams->debug = false ;
$gparams->base = Url::base() ;

if(array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true ;
}

?>

<!DOCTYPE html>
<html ng-app="YuktixApp">
<head>
    <link rel="stylesheet" href="/assets/css/material.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body ng-controller="yuktix.file.upload.mpart">

<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <?php include (APP_WEB_DIR.'/inc/title.inc'); ?>
        </div>
    </header>

    <?php /*include (APP_WEB_DIR.'/inc/toolbar.inc'); */?>
    <div class="mdl-layout-spacer"></div>

    <main class="mdl-layout__content">
        <div class="page-content">
            <div class="pad-bottom"></div>
            <?php include (APP_WEB_DIR.'/inc/page_error.inc'); ?>

            <!-- card -->
            <div class="mdl-grid">
                <div class="mdl-layout-spacer"></div>
                <div class="mdl-cell mdl-cell--6-col mdl-shadow--4dp">
                    <div class="mdl-card__title formcard mdl-color-text--white">
                        <h2 class="mdl-card__title-text formcard mdl-color-text--indigo">Create Inlet</h2>
                    </div>
                    <div class="mdl-card__supporting-text mdl-color--white">
                        <form name="InletForm">
                            <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--10-col">
                                    <h5>Lake-Stage-Flow </h5>
                                    <label class="image_input_button mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect mdl-button--colored">
                                        <i class="material-icons">file_upload</i>
                                        <input type="file" filelist-bind class="none"  name="files" multiple="true" />
                                    </label>

                                    <h5>Files:</h5>
                                    <ul>
                                        <li ng-repeat="file in files">
                                            <pre>{{ file.name}}, {{file.size/1000}} KB</pre>
                                        </li>
                                    </ul>

                                </div>
                            </div>

                            <!-- </form> -->
                    </div>
                    <div class="mdl-card__actions mdl-card--border">
                        <button ng-disabled="form1.$invalid" ng-click="" class="mdl-button mdl-js-button mdl-button--raised mdl-color-text--indigo">Finish</button>
                    </div>
                    </form>
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

    yuktixApp.controller("yuktix.file.upload.mpart", function ($scope, fupload, $window) {

        $scope.upload_files = function() {

            console.log("upload files clicked...") ;

            var metadata = {
                "key1" : "value1",
                "key2" : "value2"
            }

            var myurl = $scope.base + "/test/shim/upload/mpart.php" ;
            var payload = new FormData();
            payload.append("myfile", $scope.files[0]);
            payload.append("metadata", angular.toJson(metadata));

            fupload.send_mpart($scope.debug, myurl, payload).then(function (response) {

                var status = response.status || 500;
                var data = response.data || {};

                if ($scope.debug) {
                    console.log("API response :");
                    console.log(data);
                }

                if (status != 200 || data.code != 200) {
                    console.log("browser response object: %o" ,response);
                    var error = data.error || (status + ":error while submitting data ");
                    $scope.showError(error);
                    return;
                }


            }, function (response) {
                $scope.processResponse(response);
            });

        };

        $scope.gparams = <?php echo json_encode($gparams); ?> ;
        $scope.debug = true ;
        $scope.base = $scope.gparams.base;


    });



</script>
</body>
</html>