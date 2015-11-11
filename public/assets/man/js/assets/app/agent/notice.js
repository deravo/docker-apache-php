define(["notify"], function (Notify) {
	return {
		init: function() {
			console.log('Module\t[Notice]\tLoaded.');
			$("#notify-test").click(function(){
				$.AMUI.progress.start();
				/*
				if (Notify.isSupported)
				{
					var noti = new Notify('title', {
						body:'hello yo dawg!',
						nofifyShow:function()
						{
							console.log("it's shown!");
						},
						permissionDenied: function() {
							console.log('not accessable!');
						}
					});
					noti.show();
				}
				else
				{
					alert("you don't have a permission to call this !")
				}
				*/
				setTimeout(function(){
					$.AMUI.progress.done();
				},3000)
			})
		}
	}
});
