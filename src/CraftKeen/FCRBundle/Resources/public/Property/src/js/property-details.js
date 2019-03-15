$(function () {
	function createSlick() {
		$('.feature-slider-js,.slider-info-js').not('.slick-initialized').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 4000,
			arrows: true,
			dots: true,
			adaptiveHeight: true
		});
	}
	createSlick();
	$(window).on('resize', createSlick);
	$(".phoneField").mask("(999)-999-999999");	
	//for video
	if ($("#slide-video").length) {
		var myVideo = document.getElementById("slide-video");
	}
	
	$('.feature-slider-js').on('afterChange', function(event, slick, currentSlide, nextSlide){
		if ($("#slide-video").length) {
		  if (!myVideo.paused) {
		  		 myVideo.pause();
			}
		}
	});
});

$(function () {
	/*FORM STEP -----------------------------------------------*/
	$.validator.addMethod("loginRegex", function (value, element) {
		return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
	}, "Username must contain only letters, numbers, or dashes.");

	var $form7 = $("#propertyDetailsCTAform");
	var validator = $form7.validate({
		rules: {
			"name": {
				required: true,
				loginRegex: true,
			},
			"phone": {
				required: true
			},
			"type_of_property": {
				required: true
			},
			"square_footage": {
				required: true,
			}

		},
		messages: {
			"name": {
				required: "Required",
				loginRegex: "First Name format not valid"
			},
			"phone": {
				required: "Required"
			},
			"type_of_property": {
				required: "Required"
			}
		}
	});

	//form-step
	var sizeGroup = parseInt($(".cta-form .inputs_container .group").size());
	var baseWidth = 100 / sizeGroup;
	var step = 100 / sizeGroup;
	var count = 1;
	var bar = $(".cta-form .progress-bar-form .inner-bar");
	$(".cta-form .wrapper-form-step .count-step-block").text(count + '/' + sizeGroup);

	$(".loader-gif").hide();
	var validator = $('#leasing-cta-form').validate({
		rules: {
			"name": {
				required: true,
				loginRegex: true,
			},
			"phone": {
				required: true
			},
			"type_of_property": {
				required: true
			},
			"square_footage": {
				required: true,
			}

		},
		messages: {
			"name": {
				required: "Required",
				loginRegex: "First Name format not valid"
			},
			"email": {
				required: "Required",
			},
			"phone": {
				required: "Required"
			},
			"square_footage": {
				required: "Required"
			},
			"comment": {
				required: "Required"
			},
			"type_of_property": {
				required: "Required"
			}
		}
	});
	$(".cta-form .next-step").on('click', function (e) {
		e.preventDefault();
		$(this).parent().find('.group').each(function () {
			if ($(this).hasClass('active')) {
				var reqElement = $(this).find('input');
				if ($(this).find('select').length > 0) {
					reqElement = $(this).find('select');
				}
				if (validator.element(reqElement)) {
					if (!$(this).next().hasClass('hide')) {
						$form7.submit();
					} else {
						$(this).next().addClass('active').removeClass('hide');
						$(this).removeClass('active').addClass('hide');
						count++;
						bar.css({
							'width': (baseWidth += step) + '%'
						});
						$(".cta-form .wrapper-form-step .count-step-block").text(count + '/' + sizeGroup);
					}
					return false;
				}
			}

		});
	});
	/*--------------------------------------------------*/
	$("#propertyDetailsCTAform").on("submit", function (event) {

		event.preventDefault();
		var form = $("#leasing-cta-form");

		// console.log( $(form).serialize() );
		// return;

		$(".loader-gif").show();
		$(form).find('.message').removeClass('alert-success');
		$(form).find('.message').empty();
		count = 1;
		baseWidth = 0;
		bar.css({
			'width': (baseWidth += step) + '%'
		});
		$(".cta-form .wrapper-form-step .count-step-block").text(count + '/' + sizeGroup);
		$(form).find('.group').removeClass('active').addClass('hide');
		$(form).find('.next-step').next().addClass('active').removeClass('hide');
		// $(form).trigger( 'reset' );
		$.post($(form).attr('action'),$(form).serialize())
				.done(function (data) {
					$(".loader-gif").hide();
					//console.log(data);
					if (data.success) {
						$("#leasing-cta-form").trigger("reset");
						$(form).find('.message').addClass('alert-success');
						$(form).find('.message').html(data.message);
					}
				});
	});

	initMap($('#property_lat').val(), $('#property_lng').val(), 17 );
});