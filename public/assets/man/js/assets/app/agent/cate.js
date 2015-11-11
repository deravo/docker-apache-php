define(["jquery", "alertify", "amui", "pjax", "common", "handlebars"], function ($, alertify, AMUI, pjax, common, Handlebars) {
	var i, sCurText;
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
		})
		if  ( flag != 1 )
		{
			alertify.alert('没有找到要查找的类型');return;
		}
	}


	$(function(){
		var _backend_url   = false;
		var _backend_title = false;
		$main = $("#admin-content");
		$main.delegate('#call-popup-create-cate', 'click', function(){
			$("#popup-create-cate").modal({
				closeViaDimmer:0
			});
		});

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
								_obj.find(".rate_level1").first().text(response.data.rate_level1);
								_obj.find(".rate_level2").first().text(response.data.rate_level2);
								_obj.find(".rate_level3").first().text(response.data.rate_level3);
								_obj.find(".rate_levels").first().text(response.data.rate_levels);
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
				$("#rate_level1").val(__data.province_lvl);
				$("#rate_level2").val(__data.city_lvl);
				$("#rate_level3").val(__data.district_lvl);
				$("#rate_levels").val(__data.specila_lvl);
				_backend_url = _form[0].action;
				_form[0].action = _data.post;
			}, 'json').fail(function(response){
				alertify.error('出错啦!');
				console.error('出错啦');
			});
		});
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
		init: function(){
			$(function(){
				//~ console && console.log('Module\t[Category]\tLoaded.');
				//~ do nothing;
				$.AMUI.progress.done();
			});
		}
	}
});
