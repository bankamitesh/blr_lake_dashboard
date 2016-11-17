<?php  

	include ("lake-app.inc");
	use \com\indigloo\Url as Url;


	$gparams = new \stdClass ;
	$gparams->debug = false ;
	$gparams->base = 'http://'.$_SERVER["HTTP_HOST"];

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
	<link rel="stylesheet" href="/assets/css/mdl-selectfield.min.css">

	<style type="text/css">
		     	body {
  display: flex;
}

.file_input {
  float: left;
}

.none {
  display: none;
}
	</style>
</head>
<body ng-controller="">
	<!-- Always shows a header, even in smaller screens. -->
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
	  <div class="mdl-layout-spacer"></div>
		<?php include (WEB_ROOT_DIR.'/inc/header.inc'); ?>
		
		<main class="mdl-layout__content">
			<div class="page-content">
			<div class=""></div>
				<?php include (WEB_ROOT_DIR.'/inc/page_error.inc'); ?>
				<!-- card -->
				<div class="mdl-grid pad-bottom">
					<div class="mdl-layout-spacer"></div>
					<div class="mdl-cell mdl-cell--6-col mdl-shadow--4dp">
						<div class="mdl-card__title formcard mdl-color-text--white">
							<h2 class="mdl-card__title-text formcard">Create Inlet</h2>
						</div>
						<div class="pad-left-form-field">
							<form name="IoCreateForm">

                                <div class="pad-top-form-field">
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" id="name">
									<label class="mdl-textfield__label" for="sample3">Lake Name...</label>
								</div>
								</div><br>


								<div> 
									<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label">
										<select id="profile_information_form_dob_2i" name="profile_information_form[dob(2i)]" class="date required mdl-selectfield__select" required>
											<option value=""></option>
											<option value="IN">Storm Water Inlet</option>
											<option value="CN">Sewage Inlet</option>
											<option value="JP">Mixed Inlet</option>
											<option value="JP">Outlet</option>
										</select>
										<label for="profile_information_form_dob_2i" class="mdl-selectfield__label">Type...</label>
										<span class="mdl-selectfield__error">Input is not a empty!</span>
									</div>
								</div><br>


								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" id="name">
									<label class="mdl-textfield__label" for="sample3">Width...</label>
								</div><br>


								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" id="name">
									<label class="mdl-textfield__label" for="sample3">Height...</label>
								</div><br>

								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" id="name">
									<label class="mdl-textfield__label" for="sample3">Lattitude...</label>
								</div><br>


								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" id="name">
									<label class="mdl-textfield__label" for="sample3">Longtitude...</label>
								</div><br>


								<h5>Upload Inlet Photos </h5>
								<div class="file_input_div">
									<div class="file_input">
										<label class="image_input_button mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect mdl-button--colored">
											<i class="material-icons">file_upload</i>
											<input id="file_input_file" class="none" type="file" file-model = "myFile"/>
										</label>
									</div>
									<div id="file_input_text_div" class="mdl-textfield mdl-js-textfield textfield-demo">
										<input class="file_input_text mdl-textfield__input" type="text" disabled readonly id="file_input_text" />
										<label class="mdl-textfield__label" for="file_input_text">Choose File</label>
									</div>
								</div>
								<button ng-click = "uploadFile()" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
									Upload File
								</button><br>


                                
								<div class="pad-top-form-field"> 
								<h5>Stage-Flow Data</h5>
									<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label">
										<select id="profile_information_form_dob_2i" name="profile_information_form[dob(2i)]" class="date required mdl-selectfield__select" required>
											<option value=""></option>
											<option value="IN">Sensor Installed</option>
											<option value="CN">Constant Value</option>
											<option value="JP">Lake level Related</option>
										</select>
										<label for="profile_information_form_dob_2i" class="mdl-selectfield__label">Monitoring Status...</label>
										<span class="mdl-selectfield__error">Input is not a empty!</span>
									</div>
								</div><br>



								<div class="mdl-textfield mdl-js-textfield">
									<textarea class="mdl-textfield__input" type="text" rows= "3" id="about" ></textarea>
									<label class="mdl-textfield__label" for="text7">Sensor Details...</label>
								</div><br>

                                <h5>Select Install Date</h5>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input class="mdl-textfield__input" type="text" id="custom-date-box">
									<label class="mdl-textfield__label" for="sample3">Date...</label>
								</div><br>


                            
                               <h5>Meta Data</h5>
                               <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--5-col">
                                 <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" id="name">
									<label class="mdl-textfield__label" for="sample3">Name...</label>
								</div>	
                                </div>
                                <div class="mdl-cell mdl-cell--5-col">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" id="name">
									<label class="mdl-textfield__label" for="sample3">Value...</label>
								</div>
                                </div>
                                <div class="mdl-cell mdl-cell--2-col mdl-cell--middle">
                                <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored"><i class="material-icons">add</i></button>
                                </div>
								</div>


								<h5>SensorStage-Flow CSV Upload </h5>
								<div class="file_input_div">
									<div class="file_input">
										<label class="image_input_button mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect mdl-button--colored">
											<i class="material-icons">file_upload</i>
											<input id="file_input_file" class="none" type="file" file-model = "myFile"/>
										</label>
									</div>
									<div id="file_input_text_div" class="mdl-textfield mdl-js-textfield textfield-demo">
										<input class="file_input_text mdl-textfield__input" type="text" disabled readonly id="file_input_text" />
										<label class="mdl-textfield__label" for="file_input_text">Choose File</label>
									</div>
								</div>
								<button ng-click = "uploadFile()" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
									Upload File
								</button><br>


                                <div class="pad-top-form-field"></div>
								<h5>LakeStage-Flow CSV Upload </h5>
								<div class="file_input_div">
									<div class="file_input">
										<label class="image_input_button mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect mdl-button--colored">
											<i class="material-icons">file_upload</i>
											<input id="file_input_file" class="none" type="file" file-model = "myFile"/>
										</label>
									</div>
									<div id="file_input_text_div" class="mdl-textfield mdl-js-textfield textfield-demo">
										<input class="file_input_text mdl-textfield__input" type="text" disabled readonly id="file_input_text" />
										<label class="mdl-textfield__label" for="file_input_text">Choose File</label>
									</div>
								</div>
								<button ng-click = "uploadFile()" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
									Upload File
								</button><br>


                                <div class="pad-top-form-field"></div>

								<h5>Constant</h5>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" id="name">
									<label class="mdl-textfield__label" for="sample3">FlowRate...</label>
								</div>

                                
                                <div class="pad-top-form-field"></div>






                              


								<!-- </form> -->
							</div>
							<div class="mdl-card__actions mdl-card--border">
								<button  class="mdl-button mdl-js-button mdl-button--raised mdl-color-text--indigo">Save</button>
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
	 <script src="/assets/js/mdl-selectfield.min.js"></script>
	<script src="/assets/js/angular.min.js"></script>
	<script src="/assets/js/main.js"></script>
</body>
</html>