
// ------- Menu section ------- //
$(function () {
	function switchItemType(){
		var itemType = $('#craftkeen_cms_pagebundle_menu_itemType').val();
		switch(itemType){
			case 'page':
				$('#craftkeen_cms_pagebundle_menu_page').parents('.form-group').show();
				$('#craftkeen_cms_pagebundle_menu_url').parents('.form-group').hide();
			break;

			case 'custom':
				$('#craftkeen_cms_pagebundle_menu_url').parents('.form-group').show();
				$('#craftkeen_cms_pagebundle_menu_page').parents('.form-group').hide();
			break;
		}
	}

	switchItemType();

	$('#craftkeen_cms_pagebundle_menu_itemType').on('change', function(){
		switchItemType();
	});
});