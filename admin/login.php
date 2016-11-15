<?php  

include ("lake-app.inc");

?>

<html>
   <head>
      <link rel="stylesheet" href="/assets/css/material.min.css">
      <link rel="stylesheet" href="/assets/css/main.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

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
	<div class="mdl-cell mdl-cell--6-col mdl-shadow--4dp">
	<div class="mdl-card__title formcard mdl-color-text--white">
		<h2 class="mdl-card__title-text formcard">Login</h2>
	</div>
	<div class="mdl-card__supporting-text">
		<form action="/admin/lake/list.php">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="text" id="sample3">
				<label class="mdl-textfield__label" for="sample3">User Name...</label>
			</div>
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="password" id="sample3">
				<label class="mdl-textfield__label" for="sample3">Password...</label>
			</div>
		<!-- </form> -->
	</div>
    <div class="mdl-card__actions mdl-card--border">
		<button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" type="submit">Login</button>
	</div>
	</form>	
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