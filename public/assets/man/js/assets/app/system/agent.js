define(["jquery", "alertify", "amui", "pjax", "common", "chosen", "handlebars"], function ($, alertify, AMUI, pjax, common, chosen, Handlebars) {
	var _init = function() {
		$(function(){
			common.alertReset();
			setTimeout(function(){
				$(".chosen-select-deselect").chosen( {allow_single_deselect: true});
				$(".chosen-select-single").chosen({disable_search_threshold: 10, allow_single_deselect: true});
			}, 600);
			Handlebars.registerHelper('list', function(items, options) {
				var out = [];
				for(var i=0, l=items.length; i < l; i++) {
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
 *	运营商信息修改弹窗
 * */
		$main.delegate('.system-agent-edit', 'click', function(){
			var _data = common.parseValidator(this.dataset.settings);
			$.get(
				_data.source,
				false,
				function(response){
					if ( response.code == 2000 )
					{
						$(_data.popup).modal({ closeViaDimmer:0 });
						$(_data.popup + " h2").text(response.data.ent_name);
						$("#box_level_name").val(response.data.level_name);
						$("#box_gain_cate").val(response.data.gains.category);
						$("#box_gain_rate").val(response.data.gains.rate_lvl);
						$("#box_agent_id").val(response.data.agent_id);
						$("#box_cate_id").val(response.data.gains.cate_id);
					}
					else
					{
						alertify.alert(response.message);
					}
				},
				'json'
			);
		});
/**
 *	运营商信息修改保存
 **/
		$main.delegate("#system-agent-rate-form-submit", 'click', function(){
			var _data      = common.parseValidator(this.dataset.settings);
			var _form      = $(_data.form);
			var _popup     = $(_data.popup);
			var _post_data = _form.serializeObject();
			var _post      = true;
			var _error     = [];
			for(a in _post_data)
			{
				if ( !common.validField($("#" + a)) )
				{
					_post = false;
				};
			}
			if ( _post )
			{
				_popup.modal('close');
				common.loading.show('数据保存中');
				$.post(_form.attr("action"), _post_data, function(response){
					common.loading.close();
					alertify.alert(response.message, function(){
						if ( response.code == 2000 )
						{
							var _obj = $("#agent_" + response.data.target_id);
							_obj.find(".rate").first().text(response.data.rate);
							_obj.find(".creator").first().text(response.data.creator);
							_obj.find(".updater").first().text(response.data.updater);
							_obj.find(".update_time").first().text(response.data.update_time);
							_form[0].reset();
							//~ _obj.fadeOut(300).fadeIn(300);
						}
						else
						{
							_popup.modal({closeViaDimmer:0});
						}
					});
				}, 'json').fail(function(response){
					common.loading.close();
					alertify.alert('出错啦', function(){
						_popup.modal({closeViaDimmer:0});
					});

				});
			}
		});

/**
 *	分页与搜索
 * */
		$main.delegate("#agent_grid_page a, #btn_agent_search", "click", function(e){
			$.AMUI.progress.start();
			e.preventDefault();
			var _cate_id = $("#search_category").val(),
				_query = $.trim($("#search_enterprise").val());

			var _page = ( e.currentTarget.tagName == 'BUTTON') ? 1 : $(this).data("ciPaginationPage"),
				_get_url = '/index.php/system/agent/list/' + ( parseInt(_cate_id) > 0 ? _cate_id : 0 ) + "/" + _page;
			if ( _query != '' ) { _get_url += '?query=' + _query; }

			$.get(_get_url, false, function(result, success, response){
				$("#title_surffix").text(result.category);
				history.pushState({data: {query:_query}}, '运营商收益列表' + result.category, _get_url);

				var _grid = $("#agent_grid");
				var _data = result.data_source;

				var _source = $("#new-agent-list-template").html();
				var _temp   = Handlebars.compile(_source);
				var _html   = _temp(_data);
				_grid.empty().html(_html);
				$("#agent_grid_count").text(_data.total_rows);
				$("#agent_grid_page").html(result.pagination);
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
