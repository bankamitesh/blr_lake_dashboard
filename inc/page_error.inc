<div class="mdl-grid ">
    <div class="mdl-layout-spacer"></div>
	<div class="mdl-cell mdl-cell--bottom mdl-cell--6-col">
		<div  ng-class =" {page_error: showPageError, page_message: showPageMessage } ">
			<span ng-show="showProgressIcon"> <img src="/assets/icons/progress-icon.gif" /></span>
			<span ng-bind="pageMessage"> </span>
		</div>
	</div>
	<div class="mdl-layout-spacer"></div>
</div>
	
