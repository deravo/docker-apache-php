define(["jquery", "alertify", "amui", "pjax", "common", "handlebars", "fileupload", "lib/jquery.iframe-transport", "chosen"], function ($, alertify, AMUI, pjax, common, Handlebars, Fileupload, iframeTransport, Chosen) {
	var i, sCurText, upBtnDelegated = false;
	var __init = function(){
		$.AMUI.progress.done();
		setTimeout(function(){
			$(".chosen-select-deselect").chosen({allow_single_deselect: true});
			$(".chosen-select-single").chosen({disable_search_threshold: 10, allow_single_deselect: true});
			$("#consume_search_start_date").datepicker();
			$("#consume_search_end_date").datepicker();
		}, 1000);
	}

	var clearSelection = function () {
		$('tr.tabletrhilight').removeClass("tabletrhilight");
	}
	var cateSearchHighlight = function () {
		clearSelection();
		var flag = 0;
	    var bStart = true;
	    var searchText = $('#search_cate').val();
		if ( $.trim(searchText) == "" ) { return; }

		var regExp = new RegExp(searchText, 'g');//创建正则表达式，g表示全局的，如果不用g，则查找到第一个就不会继续向下查找了；
		$(".category").each(function(e){
			if ( regExp.test($(this).text()) )
			{
				$(this).closest('tr').addClass("tabletrhilight");
				flag = 1;
			}
		});
		if  ( flag != 1 )
		{
			alertify.alert('没有找到要查找的类型');return;
		}
	}



	$(function(){
		__init();
		var _backend_url   = false;
		var _backend_title = false;
		$main = $("#admin-content");
		$main.delegate('#call-popup-create-cate', 'click', function(){
			$("#popup-create-cate").modal({
				closeViaDimmer:0
			});
		});

		$main.delegate("#consume_search_start_date", 'focus', function(e) {
			$("#consume_search_end_date").datepicker('close')
		});
		$main.delegate("#consume_search_end_date", 'focus', function(e){
			$("#consume_search_start_date").datepicker('close');
		})

/*
		创建收益种类
*/
		$main.delegate("#system-category-form-submit", 'click', function(){
			var _data      = common.parseValidator(this.dataset.settings);
			var _popup     = $(_data.popup);
			var _form      = $(_data.form);
			var _title     = $(_data.title);
			var _post_data = _form.serializeObject();
			var _post      = true;
			var _error     = [];
			for(a in _post_data) {
				if ( !common.validField($("#" + a)) ) {
					_post = false;
				};
			}
			if ( _post ) {
				_popup.modal('close');
				common.loading.show('数据保存中');
				$.post(_form.attr("action"), _post_data, function (response) {
					alertify.alert(response.message, function () {
						if ( response.code == 2000 )
						{
							_form[0].reset();
							if ( _data.grid && response.data.data_type == 1 ) {
								var _source = $("#new-cate-template").html();
								var _temp   = Handlebars.compile(_source);
								var _html   = _temp(response.data);
								$(_html).queue(function (next) {
									$(this).appendTo($(_data.grid));
									next();
								}).show('slow');
							}
							else
							{
								var _obj = $("#cate_" + response.data.cate_id);
								_obj.find(".category").first().text(response.data.cate_name);
								_obj.find(".provider").first().text(response.data.cate_provider);
								_obj.find(".telephone").first().text(response.data.cate_telephone);
								_obj.find(".sn_prefix").first().text(response.data.cate_sn_prefix);
								_obj.find(".profit_return").first().text(response.data.cate_profit_return + "%");
								if ( _backend_url != false ) {
									_form[0].action = _backend_url;
									_title.text(_backend_title.replace('修改', '添加新的'));
								}
							}
						}
						else
						{
							_popup.modal({closeViaDimmer:0});
						}
					});
					common.loading.close();
				}, 'json').fail(function(response){
					common.loading.close();
					alertify.alert('出错啦', function(){
						_popup.modal({closeViaDimmer:0});
					});
				});
			}
		});
/************************************************************************/

/*
		修改收益种类
*/
		$main.delegate("a.system-cate-edit", 'click', function(e) {
			var _data  = common.parseValidator(this.dataset.settings);
			var _popup = $(_data.popup);
			var _form  = $(_data.form);
			var _title = $(_data.title);
			$.get(_data.source, false, function(response){
				var __data     = response.data;
				_backend_title = _title.text();
				_title.text(_backend_title.replace('添加新的', '修改'));
				_popup.modal({closeViaDimmer:0});
				$("#cate_name").val(__data.category);
				$("#cate_provider").val(__data.provider);
				$("#cate_telephone").val(__data.telephone);
				$("#cate_sn_length").val(__data.sn_length);
				$("#cate_sn_prefix").val(__data.sn_prefix);
				$("#cate_profit_return").val(__data.profit_return);
				_backend_url = _form[0].action;
				_form[0].action = _data.post;
			}, 'json').fail(function(response){
				alertify.error('出错啦!');
				console.error('出错啦');
			});
		});

/*
 *		上传消费报表
 *		2015-03-17 15:27:53
 * */

		var uploadPopupClose = function() {
			$('#u_progress .am-progress-bar').css('width', '0%').text('0%');
			$("#u_progress").addClass("am-active");
			$(".fileinput-button").removeClass("am-disabled");
			$("#dataAnalyzeTips").css('display', 'none');
			$("#u_cate_id").val(0);
			$("#u_cate_name").val('');
			$("#u_file_name").val('');
			$("#system-consume-upload-form-submit").addClass("am-disabled");
		}

		var doDataAnalyzing = function(file_name)
		{
			$(".fileinput-button").addClass("am-disabled");
			$("#dataAnalyzeTips").fadeIn('slow');
			$.get('/index.php/system/cate/consume/analyze', {file:file_name}, function(response){
				$("#dataAnalyzeTips").fadeOut('slow');
				$("#system-consume-upload-form-submit").removeClass("am-disabled");
				if (response.code == 2000)
				{
					$("#u_start_date").val(response.data.start_date);
					$("#u_end_date").val(response.data.end_date);
					$("#u_consume_total").val(response.data.total);
				}
				else
				{
					alertify.alert('出错啦', response.message);
				}
			}, 'json');
		}

		$main.delegate("a.system-cate-consume-upload", 'click', function(e){
			var _data = common.parseValidator(this.dataset.settings);
			var _popup = $(_data.popup);
			var _form  = $(_data.form);
			_popup.on('opened.modal.amui', function(){
				$("#u_cate_id").val(_data.cate_id);
				$("#u_cate_name").val(_data.cate_name);
				$('#fileupload')
					.fileupload({
						url: '/index.php/system/cate/consume/upload',
						dataType: 'json',
						done: function (e, data) {
							var rs = data.result.files[0];
							$("#u_file_name").val(rs.name);
							doDataAnalyzing(rs.name);
						},
						progressall: function (e, data) {
							var progress = parseInt(data.loaded / data.total * 100, 10);
							$('#u_progress .am-progress-bar').css(
								'width',
								progress + '%'
							).text(progress + '%');
							if ( progress >= 100 )
							{
								$("#u_progress").removeClass("am-active");
							}
						}
					})
					.prop('disabled', !$.support.fileInput)
					.parent().addClass($.support.fileInput ? undefined : 'disabled');
			});
			_popup.modal({closeViaDimmer:0});
		});

		$main.delegate(".individal-popup-close", 'click', function(e) {
			var _data = common.parseValidator(this.dataset.settings);
			var _popup = $(_data.popup);
			var _form  = $(_data.form);
			uploadPopupClose();
			_popup.modal('close');
			_form[0].reset();
		});


/*
		保存报表信息
*/
		$main.delegate("#system-consume-upload-form-submit", 'click', function(){
			var _data      = common.parseValidator(this.dataset.settings);
			var _popup     = $(_data.popup);
			var _form      = $(_data.form);
			var _post_data = _form.serializeObject();
			var _post      = true;
			var _error     = [];
			_popup.modal('close');
			common.loading.show('数据保存中');
			$.post(_form.attr("action"), _post_data, function (response) {
				if ( response.code != 2000 )
				{
					common.loading.close(); uploadPopupClose(); _form[0].reset();
					alertify.alert(response.message);
					//~ alertify.alert(response.message, function(){ _popup.modal({closeViaDimmer:0}); });
				} else {
					$("#uploadPopupClose .am-modal-hd").html("保存完成，开始数据统计...");
					$.get('/index.php/system/cate/consume/statistics/' + response.data, function(result){
						common.loading.close();
						alertify.alert(result.message, function(){
							if (result.code == 2000 )
							{
								uploadPopupClose(); _form[0].reset();
							} else {
								//~ _popup.modal({closeViaDimmer:0});
							}
						});
					}, 'json');
				}
			}, 'json').fail(function(response){
				common.loading.close(); uploadPopupClose(); _form[0].reset();
				alertify.alert('出错啦');
				//~ alertify.alert('出错啦', function(){ _popup.modal({closeViaDimmer:0}); });
			});
		});
/************************************************************************/

/***********************************************************************/

/*
		种类会员卡跳转
*/
		$main.delegate("#systen-cate-list a.pjax", 'click', function(){
			$_ul = $("#collapse-nav-cate");
			$_ul.find("span.am-icon-gear:first").removeClass("am-icon-gear am-icon-spin")
			$_ul.find("li").removeClass("admin-sub-check");
			$__ul = $("#collapse-nav-vcard");
			$_ul.collapse("close");
			$__ul.collapse("open");
			$__ul.bind('opened.collapse.amui', function(){
				$__li = $(this).find("li:first");
				$__li.addClass("admin-sub-check");
				$__li.find("span.am-margin-right:first").addClass("am-icon-gear am-icon-spin");
				$__ul.unbind("opened.collapse.amui");
			});
			return;
		});

/**
		頁面內搜索類型
*/
		$main.delegate("#btn_cate_search", 'click', cateSearchHighlight);
		$main.delegate("#search_cate", "keydown", function(e){
			var key = e.which;
			if (key == 13) { cateSearchHighlight(); }
		});
	});

	return {
		init: __init
	}
});
