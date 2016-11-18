<?php  

  include ("lake-app.inc");
  include(APP_WEB_DIR.'/inc/header.inc');

  use \com\indigloo\Url ;

  $gparams = new \stdClass ;
  $gparams->debug = false ;
  $gparams->base = Url::base() ;

  if(array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true ;
  }

?>
<html ng-app="YuktixApp">
   <head>
      <link rel="stylesheet" href="/assets/css/material.min.css">
      <link rel="stylesheet" href="/assets/css/main.css">
      <link rel="stylesheet" href="/assets/css/mdl-selectfield.min.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
   </head>
<body ng-controller="yuktix.admin.lake.create">
   <!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

     <?php include (APP_WEB_DIR.'/inc/toolbar.inc'); ?>

<main class="mdl-layout__content">

<div class="page-content">
<!-- card -->
<div class="mdl-grid pad-bottom">

 <div class="mdl-layout-spacer"></div>

	 <div class="mdl-cell mdl-cell--6-col mdl-shadow--4dp">

	     <div class="mdl-card__title formcard mdl-color-text--white">
		      <h2 class="mdl-card__title-text formcard">Create Lake</h2>
	     </div>

	   <div class="pad-left-form-field">

		    <form action="">
            <div class="pad-top-form-field"></div>
			      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				      <input class="mdl-textfield__input" type="text" id="name">
				      <label class="mdl-textfield__label" for="sample3">Lake Name...</label>
			      </div><br>


			      <div class="mdl-textfield mdl-js-textfield">
               <textarea class="mdl-textfield__input" type="text" rows= "3" id="about" ></textarea>
               <label class="mdl-textfield__label" for="text7">About...</label>
            </div>


            <h5>Lake Photo Uplaod</h5>
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
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="lat">
                <label class="mdl-textfield__label" for="sample3">Lattitude...</label>
              </div>
            </div>



            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input" type="text" id="long">
              <label class="mdl-textfield__label" for="sample3">Longtitude...</label>
            </div><br>
			


            <div class="mdl-textfield mdl-js-textfield">
               <textarea class="mdl-textfield__input" type="text" rows= "3" id="address" ></textarea>
               <label class="mdl-textfield__label" for="text7">Address...</label>
            </div><br>



            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input" type="text" id="long">
              <label class="mdl-textfield__label" for="sample3">Max Area...</label>
            </div><br>



            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input" type="text" id="long">
              <label class="mdl-textfield__label" for="sample3">Max Volume...</label>
            </div><br>



            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input" type="text" id="long">
              <label class="mdl-textfield__label" for="sample3">Rechange Rate...</label>
            </div>

                
            <h5>Usage</h5>
            <div class="mdl-grid mdl-grid--no-spacing">

              <div class="mdl-cell mdl-cell--3-col">
                <label class="mdl-checkbox mdl-js-checkbox" for="checkbox1">
                  <input type="checkbox" id="checkbox1" class="mdl-checkbox__input">
                  <span class="mdl-checkbox__label">Waliking</span>
                </label>
              </div>

              <div class="mdl-cell mdl-cell--3-col">
               <label class="mdl-checkbox mdl-js-checkbox" for="checkbox2">
                <input type="checkbox" id="checkbox2" class="mdl-checkbox__input" >
                <span class="mdl-checkbox__label">Birding</span>
              </label>
            </div>

            <div class="mdl-cell mdl-cell--3-col">
              <label class="mdl-checkbox mdl-js-checkbox" for="checkbox3">
                <input type="checkbox" id="checkbox3" class="mdl-checkbox__input" >
                <span class="mdl-checkbox__label">Fishing</span>
              </label>
            </div>

            <div class="mdl-cell mdl-cell--3-col">
              <label class="mdl-checkbox mdl-js-checkbox" for="checkbox4">
                <input type="checkbox" id="checkbox4" class="mdl-checkbox__input" >
                <span class="mdl-checkbox__label">Livestock</span>
              </label>
            </div>

            <div class="mdl-cell mdl-cell--3-col">
             <label class="mdl-checkbox mdl-js-checkbox" for="checkbox5">
              <input type="checkbox" id="checkbox5" class="mdl-checkbox__input" >
              <span class="mdl-checkbox__label">Idol Immersion</span>
            </label>
            </div>

          <div class="mdl-cell mdl-cell--3-col">
           <label class="mdl-checkbox mdl-js-checkbox" for="checkbox6">
            <input type="checkbox" id="checkbox6" class="mdl-checkbox__input" >
            <span class="mdl-checkbox__label">Swimming</span>
          </label>
         </div>

        <div class="mdl-cell mdl-cell--3-col">
         <label class="mdl-checkbox mdl-js-checkbox" for="checkbox7">
          <input type="checkbox" id="checkbox7" class="mdl-checkbox__input" >
          <span class="mdl-checkbox__label">Drinking</span>
        </label>
      </div>

      <div class="mdl-cell mdl-cell--3-col">
        <label class="mdl-checkbox mdl-js-checkbox" for="checkbox8">
          <input type="checkbox" id="checkbox8" class="mdl-checkbox__input" >
          <span class="mdl-checkbox__label">Other</span>
        </label>
      </div>

    </div>


          
                 <div class="pad-top-form-field"> 
                  <div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label">
                    <select id="profile_information_form_dob_2i" name="profile_information_form[dob(2i)]" class="date required mdl-selectfield__select" required>
                      <option value=""></option>
                      <option value="IN">BBMP</option>
                      <option value="CN">BDA</option>
                      <option value="JP">CITIZEN GROUP</option>
                      <option value="JP">FOREST DEPT</option>
                      <option value="JP">LDA</option>
                      <option value="JP">OTHER</option>
                    </select>
                    <label for="profile_information_form_dob_2i" class="mdl-selectfield__label">Lake Management...</label>
                    <span class="mdl-selectfield__error">Input is not a empty!</span>
                  </div>
                </div>
                  
                  
                  <h5>Agency Details Upload</h5>
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
                    <div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label">
                    <select id="profile_information_form_dob_2i" name="profile_information_form[dob(2i)]" class="date required mdl-selectfield__select" required>
                      <option value=""></option>
                      <option value="IN">Storm Water Fed</option>
                      <option value="CN">Sewage Fed</option>
                      <option value="JP">Mixed Inflow</option>
                    </select>
                    <label for="profile_information_form_dob_2i" class="mdl-selectfield__label">Lake Type...</label>
                    <span class="mdl-selectfield__error">Input is not a empty!</span>
                  </div>
                 </div><br>

		</form>
	</div>
    <div class="mdl-card__actions mdl-card--border">
		<button class="mdl-button mdl-js-button mdl-button--raised mdl-color-text--indigo" type="submit">Save</button>
	</div>
	</div>
	<div class="mdl-layout-spacer"></div>
</div> 
<!-- end card -->        
<div class="pad-bottom"></div>>

         </div>
         <?php include (APP_WEB_DIR.'/inc/footer.inc'); ?>
      </main>
   </div>
    <script src="/assets/js/material.min.js"></script>
    <script src="/assets/js/mdl-selectfield.min.js"></script>
    <script src="/assets/js/angular.min.js"></script>
    <script src="/assets/js/main.js"></script>
     
</body>
</html>