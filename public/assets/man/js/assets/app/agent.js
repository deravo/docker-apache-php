define(["jquery", "alertify", "amui", "pjax", "common"], function ($, alertify, AMUI, pjax, common) {
	$(function() {
		$(document)
			.ajaxError(function(e, x, s){
				console.log(x.responseText);
			})
			.ajaxComplete(function(e, x, s) {
				var rs = false;
				try {
					rs = JSON.parse(x.responseText);
					if ( rs.code > 400 && rs.code < 1000 )
					{
						if (rs.data)
						{
							window.location.href = rs.data;
						}
					}
				} catch(e) {
					//do nothing;
				}
			});
		common.alertReset();
		$('#create_cancel').on('click', function(e) {
			window.location.href = $(this).data('target');
			return false;
		});
		$("a.pending").on('click', function(){
			alertify.error('<b>' + this.innerText + '</b> 开发中');
			return false;
		});
		$("a.logout_arch").on('click', function(e){
			e.preventDefault();
			var _href = e.delegateTarget.href;
			alertify.confirm("你确认退出本次操作？", function(result){
				if ( result ) { window.location.href = _href; }
			});
		});
		$("#admin-main-body").resize(function() {
			var _height = $(this).height();
			if ( _height > $(".admin-content").first().height() )
			{
				$(".admin-content").first().height(_height);
			}
		});
		$("#admin-content").delegate(".act-popup-close", 'click', function(){
			var _data = common.parseValidator(this.dataset.settings);
			try{
				$(_data.popup).modal('close');
				$(_data.form)[0].reset();
			}
			catch(e)
			{
				console && console.error('something was wrong!');
			}
		});
		$("ul.am-collapse").on("open.collapse.amui", function(e){
			$(this).siblings('a').find("span.am-margin-right").first().removeClass("am-icon-angle-down").addClass("am-icon-angle-right");
		}).on("close.collapse.amui", function(e){
			$(this).siblings('a').find("span.am-margin-right").first().removeClass("am-icon-angle-right").addClass("am-icon-angle-down");
		});
		$("#admin-sidebar a.pjax").each(function() {
			$(this).click(function() {
				$_ul = $(this).closest('ul');
				$__ul = $("span.am-icon-gear").closest("ul");
				$__li = $("span.am-icon-gear").closest("li");
				if ( $__li.attr("href") != $(this).attr("href") )
				{
					$__ul.find("span.am-icon-gear").removeClass("am-icon-gear am-icon-spin");
					$__ul.find("li").removeClass("admin-sub-check");
				}
				if ( $__ul.attr("id") != $_ul.attr("id") )
				{
					if ($__ul.hasClass('am-collapse')){$__ul.collapse('close');}
					if ($_ul.hasClass('am-collapse')){$_ul.collapse('open');/*{parent:true,toggle: true}*/}
				}
				$(this).closest('li').addClass("admin-sub-check");
				$(this).find("span.am-margin-right").first().addClass("am-icon-gear am-icon-spin");
			});
		});
		$.pjax({
			selector:"a.pjax",
			container:"#admin-content",
			show:'fade',
			cache:false,
			storage:false,
			titleSuffix:'',
			timeout:2000,
			filter:function(){},
			callback:function(status){
			}
		});
		$("#admin-content").bind('pjax.start', function() {
			$.AMUI.progress.start();
		}).bind('pjax.end', function(response) {
			var _path = window.location.pathname;
			var _cls  = _path.replace("index.php/", "");
			_cls = _cls.substring(1, _cls.lastIndexOf('/'));
			var __cls = _cls.split("/");
			var _rb   = false;
			for(var i = __cls.length, l = 0, _d = __cls; i >= l; i-- )
			{
				if ( _d[i] && !isNaN(_d[i]) )
				{
					_rb = true;
					__cls.pop();
				}
			}
			if ( _rb )
			{
				_cls = __cls.join("/");
				var _cls = _cls.substring(0, _cls.lastIndexOf('/'));
			}

			if ( _cls.indexOf("/") > 1 && _cls.length > _cls.indexOf("/") && _cls != 'agent')
			{
				require(["app/" + _cls], function(cls){
					try {
						cls.init();
					} catch(e) {
						console && console.log(_path + '|' + _cls + '建议加装初始化入口函数');
					}
				});
			}
			$.AMUI.progress.done();
		});
	});

	return {
		init: function(){
			$(function(){
				$.AMUI.progress.done();
			});
		}
	}
});
