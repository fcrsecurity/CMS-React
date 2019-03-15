$(function(){
	var timestamp = 0;
	var timeoutHandle = null;
	$('#menu').on('input', '.mm-search input', function() {
		var text = $(this).val();
		var currentTime = new Date().getTime();
		if ((currentTime - timestamp) > 1200){
			sendSearchRequest(text, locale);
		}
		else {
			window.clearTimeout(timeoutHandle);
			timeoutHandle = window.setTimeout(function(){
				sendSearchRequest(text, locale);
			}, 1200);
		}
		timestamp = currentTime;
		return false;
	});

	function sendSearchRequest(text, locale){
		$.ajax({
			type: "POST",
			url: "/" + locale + "/ajaxsearch",
			data: {text: text},
			success: function (msg) {
				var content = '';
				if (msg.length > 0) {
					msg.forEach(function (item) {
						content += '<li class=""><a href="' + item.link + '">' + item.linkName + '</a></li>';
					});

					$('.mm-listview').html(content);
				}
				else {
					$('.mm-listview li').addClass('mm-hidden');
					$('.mm-noresultsmsg').removeClass('mm-hidden');
				}
			}
		});
	}
})