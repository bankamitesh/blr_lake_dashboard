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
                                        <h5>Constant</h5>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="name">
                                            <label class="mdl-textfield__label" for="sample3">FlowRate...</label>
                                        </div>
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
</body>
</html>