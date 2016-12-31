<?php

  $ui_message = "" ;
  
  if(array_key_exists("message", $_GET)) {
    $ui_message = $_GET["message"] ;
  } else {
    $ui_message = "we apologize for the inconvenience" ;
  }



?>

<!DOCTYPE html>
 <html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
    <head>
        <meta charset="utf-8">
        <title>Internal Server Error</title>
        <link href="/site/error.css" rel="stylesheet" media="screen">
</head>

<body>

<div id="error" class="error_code_500">
    <div class="error_message">
        We had a problem processing that request. <a href="/">Go to the Homepage</a> »
    </div>
    <div class="error_bubble">
        <div class="error_code">500<br><span>ERROR</span></div>
        <div class="error_quote"><?php echo $ui_message; ?></div>
    </div>
    <div class="error_arrow"></div>
    <div class="error_attrib"> <span>What does the code say?</span>  
    </div>
    <div class="clear"></div>
</div>


</body></html>
