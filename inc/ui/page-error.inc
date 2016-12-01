<div id="error-container">
	<div  ng-class =" {page_error: showPageError, page_message: showPageMessage } ">
		<span ng-show="showProgressIcon"> <img src="/assets/icons/progress-icon.gif" /></span>
		<span ng-show="showPageError"> <i class="material-icons">adb</i></span>
		<span ng-show="showPageMessage"> <i class="material-icons">comment</i></span>
		<span ng-bind="pageMessage"> </span>
	</div>
</div>

	
	
	
