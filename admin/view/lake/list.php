<?php  

include ("lake-app.inc");

?>
<html>
   <head>
      <link rel="stylesheet" href="/assets/css/material.min.css">
      <link rel="stylesheet" href="/assets/css/main.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <style type="text/css">
        .mdl-data-table {
            table-layout:fixed;
            width:100%; 
            }
            .mdl-data-table th, td{
  text-align: center !important;
}
    </style>

   </head>
<body>
   <!-- Always shows a header, even in smaller screens. -->
   <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
   <?php include (WEB_ROOT_DIR.'/inc/header.inc'); ?>
      <main class="mdl-layout__content">
         <div class="page-content">
<!-- card -->
<div class="mdl-grid pad-bottom">
<div class="mdl-layout-spacer"></div>
	<div class="mdl-cell mdl-card mdl-cell--6-col mdl-shadow--4dp">
	<div class="mdl-card__title formcard mdl-color-text--white">
		<h2 class="mdl-card__title-text formcard">Lakes</h2>
		<div class="mdl-layout-spacer"></div>
      <span>Create</span>&nbsp;
		<h2 class="mdl-card__title-text formcard">
        
        <form action="/admin/lake/create.php"><button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect"><i class="material-icons">add</i></button> </form
       >
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
          <?php include (WEB_ROOT_DIR.'/inc/footer.inc'); ?>
      </main>
   </div>
    <script src="/assets/js/material.min.js"></script>
</body>
</html>