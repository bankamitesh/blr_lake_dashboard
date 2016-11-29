<?php 

    // codes for picklists 
    include ('lake-app.inc');
	include (APP_WEB_DIR . '/inc/header.inc');
	
    use \com\indigloo\Configuration as Config;
    use \com\indigloo\mysql as MySQL;

	use \com\indigloo\Logger ;
	use \com\indigloo\util\StringUtil as StringUtil ;

	use \com\yuktix\auth\Login as Login ;
    use \com\indigloo\exception\UIException as UIException;

    set_exception_handler('webgloo_ajax_exception_handler');
	$gWeb = \com\indigloo\core\Web::getInstance ();
	
    $lakeUsages = [
        ["id" => 1, "value" => "Walking" ],
        ["id" => 2, "value" => "Birding" ],
        ["id" => 3, "value" => "Idol Immersion" ],
         ["id" => 4, "value" => "Swimming" ],
        ["id" => 5, "value" => "Laundry" ],
        ["id" => 6, "value" => "Livestock" ],
        ["id" => 7, "value" => "Drinking" ],
        ["id" => 8, "value" => "Others" ]];

    $lakeAgencies = [
        ["id" => 1, "value" => "BDA" ],
        ["id" => 2, "value" => "BBMP" ],
        ["id" => 3, "value" => "LDA" ],
        ["id" => 4, "value" => "Citizen Group" ],
        ["id" => 5, "value" => "Forest Department" ]] ;
       
    $lakeTypes = [
        ["id" => 1, "value" => "Storm water fed"],
        ["id" => 2, "value" => "Sewage fed"],
        ["id" => 3, "value" => "Mixed inflow"]
    ] ;


    $inletTypes = [
        ["id" => 1, "value" => "Storm water fed"],
        ["id" => 2, "value" => "Sewage fed"],
        ["id" => 3, "value" => "Mixed inflow"]
    ] ;


    $featureMonitoring = [
        ["id" => 1, "value" => "Sensors Installed"],
        ["id" => 2, "value" => "Related to Lake Level"],
        ["id" => 3, "value" => "Constant Value"]
    ] ;


    $codes = [
        "lakeAgencies" => $lakeAgencies,
        "lakeTypes" => $lakeTypes ,
        "featureMonitoring" => $featureMonitoring,
        "lakeUsages" => $lakeUsages];


    // API response 
    $responseObj = new \stdClass ;
    $responseObj->code = 200;
    $responseObj->result = $codes ;

    echo json_encode($responseObj) ;
    exit(0) ;
?>