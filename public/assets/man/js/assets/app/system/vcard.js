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
 *	添加会员卡
 * */
		$main.delegate("#call-popup-create-vcard", "click", function(){
			$("#popup-create-vcard").modal({ closeViaDimmer:0 });
		});
		$main.delegate("#system-vcard-create-submit", 'click', function(){
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
						}
						else
						{
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
 *	修改会员卡
 * */
		$main.delegate('.system-card-edit', 'click', function(){
			var _data = $(this).data("settings");//common.parseValidator($(this).data("settings"));
			var _data_agents = $(this).data("agents");
			/*
			if ( parseInt(_data_agents.agent_l_id) > 0 ) {
				if ( parseInt(_data_agents.agent_m_id) > 0 ) {
					if ( parseInt(_data_agents.agent_s_id) > 0 )
					{
						_data.agent_id = _data_agents.agent_s_id;
					} else {
						_data.agent_id = _data_agents.agent_m_id;
					}
				} else {
					_data.agent_id = _data_agents.agent_l_id;
				}
			} else {
				_data.agent_id = 0;
			}
			*/
			//~ _data.agent_id = _data_agents.agent_l_id; 

			var _item = $("#vcard_" + _data.card_id);
			var _itemData = {
				card_id : _data.card_id,
				cate_id : _data.cate_id,
				cate_name : _item.find(".cate_name").first().text(),
				card_number : _item.find(".card_number").first().text()
				//~ ,agent_id : _data_agents.agent_l_id
			}

			$(_data.popup).modal({ closeViaDimmer:0 });
			var _form = $(_data.form);
			_form[0].card_id.value = _itemData.card_id;
			_form[0].cate_id.value = _itemData.cate_id;
			_form[0].cate_name.value = _itemData.cate_name;
			_form[0].card_number.value = _itemData.card_number;
			//~ _form[0].agent_id.value = _itemData.agent_id;
		});
		$main.delegate("#system-vcard-update-submit", 'click', function(){
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
							var _obj = $("#vcard_" + _post_data.card_id);
							_obj.find(".card_number").first().text(_post_data.card_number);
							_obj.find(".cate_name").first().text(_post_data.cate_name);
							//~ _obj.find(".agent_name").first().text(response.data.agent_name);
							//~ _obj.find("a.system-card-edit").first().data("settings", '{"popup":"#popup-update-vcard", "form":"#act_update_vcard_form", "cate_id":"' + _post_data.cate_id + '", "card_id":"' + _post_data.card_id + '", "agent_id":"' + _post_data.agent_id + '", "source":"/index.php/system/vcard/get/' + _post_data.card_id + '"}')
							_obj.find("a.system-card-edit").first().data("settings", '{"popup":"#popup-update-vcard", "form":"#act_update_vcard_form", "cate_id":"' + _post_data.cate_id + '", "card_id":"' + _post_data.card_id + '", "source":"/index.php/system/vcard/get/' + _post_data.card_id + '"}')
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
				//~ _agent_id = $("#search_agent").val(),
				_is_bind = $("#search_bind").val(),
				_query = $("#search_card").val();
			//~ if ( parseInt(_agent_id) > 0 ) { _get_data.agent_id = _agent_id; }
			if ( parseInt(_is_bind) > -1 ) { _get_data.is_bind = _is_bind; }
			if ( $.trim(_query) != '' ) { _get_data.query = _query; }

			var _page = ( e.currentTarget.tagName == 'BUTTON') ? 1 : $(this).data("ciPaginationPage"),
				_get_url = '/index.php/system/vcard/list/' + ( parseInt(_cate_id) > 0 ? _cate_id : 0 ) + "/" + _page;
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
