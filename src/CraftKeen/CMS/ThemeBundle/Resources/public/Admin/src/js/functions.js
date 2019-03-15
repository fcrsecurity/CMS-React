$(function () {
	$("#menu-toggle").click(function (e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
	});

	$(".form-delete").submit(function(e){
		var form = this;
        e.preventDefault();
		toastr.info(
				"<br /><button type='button' id='confirmationRevertYes' class='btn btn-danger'>Yes</button>" +
				"&nbsp;<button type='button' id='confirmationRevertNo' class='btn btn-info'>Cancel</button>", 'Are you sure you want to delete this item?',
		{
			closeButton: false,
			allowHtml: true,
			onShown: function (toast) {
				$("#confirmationRevertYes").click(function () {
					form.submit();
				});
			}
		});
    });

	$('.rejectionComment').hide();
	$('.btn-reject').on('click', function (e) {
	    if ($('.rejectionComment').val().length == 0) {
            e.preventDefault();
        }
        $('.rejectionComment').fadeIn();
    });
    /**
	 * On change listener for all text filed needs to be converter into a slug.
     */
	$('.text-to-slug').change(function(){
        var data = $(this).data();
        if ( undefined !== data.toSlugTarget ) {
        	var target = $('.'+data.toSlugTarget);
			if ( true == data.toSlugAllowOverwrite ) {
                target.val( Helper.textToSlug($(this).val()) );
			} else {
				if ( target.val().length === 0 ) {
                    target.val( Helper.textToSlug($(this).val()) );
				}
			}
        }
	});
});

