(function(e)
{
	require.config({
		baseUrl:"/assets/man/js/assets",
		//urlArgs:"bust=" + ~(-(new Date)/36e5),
		urlArgs:"bust=" + -Math.round(new Date().getTime()/1000),
		paths:{
			"jquery":"lib/jquery.min",
			"amui":"lib/amazeui.min",
			//~ "amui":"lib/amazeui",
			"alertify":"lib/jquery.alertify.min",
			"common":"common"
		},
		shim:{
			"alertify":['jquery'],
			"amui": ['jquery']
		}
	});
	require(
		["app/login"],
		function(login)
		{
			try
			{
				login.init();
			}
			catch(t)
			{
				console && console.error("Module Initializing Failed!")
			}
		}
	);
}(window));
