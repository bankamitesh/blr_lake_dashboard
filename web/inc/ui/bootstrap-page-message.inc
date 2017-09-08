<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="/test/bootstrap/assets/css/style.css" />
</head>
<div id="page-message-container"> 
    <div ng-class="{page_message : showPageMessage, page_error: showPageError }">
        <p ng-bind="pageMessage"> </p>
    </div>
</div>