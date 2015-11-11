(function(e)
{
	require.config({
		baseUrl:"/assets/man/js/assets",
		urlArgs:"bust=" + -(~Math.round(new Date().getTime()/1000)),
		paths:{
			"jquery":"lib/jquery.min",
			//~ "amui":"lib/amazeui.min",
			"amui":"lib/amazeui",
			"chosen":"lib/amazeui.chosen.min",
			"alertify":"lib/jquery.alertify.min",
			"pjax":"lib/jquery.pjax",
			"common":"common",
			"handlebars":"lib/handlebars.min",
			"fileupload":"lib/jquery.fileupload"
			//~ "jqueryUI":"lib/vendor/jquery.ui.widget"
		},
		shim:{
			//~ "jqueryUI": {deps: ['jquery'], export: 'jqueryUI'},
			"alertify":['jquery'],
			"pjax":['jquery'],
			"amui": {
				deps:['jquery'],
				export: 'AMUI'
			},
			"chosen":{
				deps:['jquery'],
				export:'chosen'
			},
			"handlebars": {
				export: "Handlebars"
			},
			"fileupload": {
				deps:['jquery'],
				export:"fileupload"
			}
		}
	});
	var _requring = ['app/system'];
	var _path = window.location.pathname;
	_cls = _path.substring(1, _path.lastIndexOf('/')).split("/");
	if ( _cls[0].indexOf(".php") > -1 )
	{
		_cls.shift();
	}
	if ( _cls.length > 1 && _cls[1] )
	{
		_requring.push('app/' + _cls[0] + '/' + _cls[1]);
	}
	require(_requring);
}(window));
