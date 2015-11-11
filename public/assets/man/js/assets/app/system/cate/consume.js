define(["jquery", "alertify", "amui", "pjax", "common", "chosen", "handlebars"], function ($, alertify, AMUI, pjax, common, Chosen, Handlebars) {

	$(function(){
		$main = $("#admin-content");
		common.alertReset();

		setTimeout(function(){
			$(".chosen-select-deselect").chosen( {allow_single_deselect: true});
			$(".chosen-select-single").chosen({disable_search_threshold: 10, allow_single_deselect: true});
			$("#consume_search_start_date").datepicker();
			$("#consume_search_end_date").datepicker();

		}, 600);


	});

	return {
		init: function(){
			$.AMUI.progress.done();
		}
	}
});
