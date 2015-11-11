define(['jquery','alertify', 'common', 'amui'], function ($, alertify, common, AMUI) {
	return {
		init: function()
		{
			$(".admin-login-switch").on('click', function(){
				if ( this.dataset.href )
				{
					window.location.href = this.dataset.href;
				}
				return false;
			});
			$("#imgcode_span img").click(function(){
				this.src = '/index.php/vcode/' + ~(-(new Date)/36e5);
			});
			$("#login_form").on('submit', function(){
				if (event && event.preventDefault)
				{
					event.preventDefault();
				}
				else {
					window.event.returnValue = false;
				}
				common.alertReset();
				var _post_data = $(this).serializeObject();
				_post_data.password = common.MD5(_post_data.password);
				$.post(
					this.action,
					_post_data,
					function(response)
					{
						
						if (response.code == 2000)
						{
							window.location.href = "/";//response.data;
						}
						else
						{
							alertify.alert(response.message, function(){
								/*if ( $("#imgcode_span").css('display') != 'none') {
									$("#imgCode").trigger('click');
								}
								else
								{
									$("#imgcode_span").fadeIn(1000);//show();
								}*/
							});
						}

					},'json')
					.fail(
						function(response)
						{
							console && console.error(response.responseText)
						}
					)
			});
		}
	}
});
