$(function(){
	$(".job-form-apply").hide();
    
    $("#apply-button").on('click', function() {
        $(this).fadeOut();
        $(".job-form-apply").fadeIn('slow');
    });

    var errorInputs = $("form[name=craftkeen_fcrbundle_careerpositionsubmission] span.help-block+input");
    if (0 !== errorInputs.length) {
        $("#apply-button").fadeOut();
        $(".job-form-apply").fadeIn();
        $(errorInputs[0]).focus();
    }
});
