$(function() {
	var TEXT = $('.portfolio .tg-list-item .checkbox label').text();
	var ID_INPUT = $('.portfolio .checkbox .tgl-ios').attr('id');
	$('.portfolio .tg-list-item').append($('.portfolio .checkbox .tgl-ios'))
								 .append('<label for='+ID_INPUT+' class="tgl-btn small"></label>')
								 .append('<span>'+TEXT+'</span>');
	$('.portfolio .tg-list-item .checkbox').remove();
});