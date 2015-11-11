define(["jquery", "alertify", "amui", "pjax", "common", "chosen", "handlebars"], function ($, alertify, AMUI, pjax, common, chosen, Handlebars) {
	var _init = function() {
		$(function(){
			common.alertReset();
			//~ 延迟渲染选择框
			setTimeout(function(){
				$(".chosen-select-deselect").chosen( {allow_single_deselect: true});
				$(".chosen-select-single").chosen({disable_search_threshold: 10, allow_single_deselect: true});
			}, 600);
			Handlebars.registerHelper('list', function(items, options) {
				var out = [];
				for(var i = 0, l = items.length; i < l; i++) {
					out[i] = options.fn(items[i]);
				}
				return out.join("");
			});
			$.AMUI.progress.done();
		});

	}

	$(function(){
		_init();
		$main = $("#admin-content");
/**
 *	会员卡绑定
 * */
		$main.delegate("#call-popup-bind-vcard", "click", function(){
			$("#popup-bind-vcard").modal({ closeViaDimmer:0 });
		});
		$main.delegate("#agent-vcard-bind-submit", 'click', function(){
			var _data      = common.parseValidator(this.dataset.settings);
			var _popup     = $(_data.popup);
			var _form      = $(_data.form);
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
							var _grid = $(_data.grid);
							var _source = $("#new-vcard-template").html();
							var _temp   = Handlebars.compile(_source);
							var _html   = _temp(response.data);
							_grid.empty().html(_html);

							$(_data.count).text(response.data.total_rows);
							$(_data.pagination).html(response.data.pagination);
						} else {
							_popup.modal({closeViaDimmer:0});
						}
					});
					common.loading.close();
				}, 'json').fail(function(response){
					common.loading.close();
					alertify.alert('出错啦', function(){;
						_popup.modal({closeViaDimmer:0});
					});
				});
			}
		});


/**
 *	分页与搜索
 * */
		$main.delegate("#vcard_grid_page a, #btn_vcard_search", "click", function(e){
			$.AMUI.progress.start();
			e.preventDefault();
			var _get_data = {};
			var _cate_id = $("#search_cate").val(),
				_query = $("#search_card").val();
			if ( $.trim(_query) != '' ) { _get_data.query = _query; }

			var _page = ( e.currentTarget.tagName == 'BUTTON') ? 1 : $(this).data("ciPaginationPage"),
				_get_url = '/index.php/agent/vcard/list/' + ( parseInt(_cate_id) > 0 ? _cate_id : 0 ) + "/" + _page;
			_get_url = common.URL.setParam(_get_url, _get_data);

			$.get(_get_url, false, function(result, success, response){
				$("#title_surffix").text(result.category);
				history.pushState({data: _get_data}, '会员卡列表' + result.category, _get_url);

				var _grid = $("#vcard_grid");
				var _data = result.data_source;

				var _source = $("#new-vcard-template").html();
				var _temp   = Handlebars.compile(_source);
				var _html   = _temp(_data);
				_grid.empty().html(_html);
				$("#vcard_grid_count").text(_data.total_rows);
				$("#vcard_grid_page").html(result.pagination);
				$.AMUI.progress.done();
			}, 'json').fail(function(response){
				alertify.error('出错啦');
				$.AMUI.progress.done();
			});
		});
	});

	return {
		init:function(){
			_init();
		}
	}
});
