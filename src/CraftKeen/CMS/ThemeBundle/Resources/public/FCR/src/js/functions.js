$(function () {
    var globals = {
        gallerySettingsStandard: {
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: !0,
            autoplaySpeed: 3e3,
            arrows: !0,
            dots: !0,
            adaptiveHeight: !1
        }, gallerySettingsMultipleItems: function (a, b, c, d) {
            return void 0 == c && (c = !1), void 0 == d && (d = [{
                breakpoint: 960,
                settings: {slidesToShow: 1, slidesToScroll: 1, infinite: !0}
            }]), {
                slidesToShow: a,
                slidesToScroll: b,
                arrows: c,
                dots: !1,
                autoplay: !0,
                autoplaySpeed: 3e3,
                adaptiveHeight: !1,
                responsive: d
            }
        }
    };

    $('.slick-5-item-line').slick(globals.gallerySettingsMultipleItems(5, 1));
    $('.slick-3-item-line').slick(globals.gallerySettingsMultipleItems(3, 1));
    $('.slick-4-item-line').slick(globals.gallerySettingsMultipleItems(4, 1));
    $('.slick-3-item-line-arrow').slick(globals.gallerySettingsMultipleItems(3, 1, true));
    $('.slick-4-item-line-arrow').slick(globals.gallerySettingsMultipleItems(4, 1, true));

    $(document).on('click', '.yamm .dropdown-menu', function (e) {
        e.stopPropagation()
    });

    $('#dl-menu').append($('.nav-bar-desctop').parent('nav').html());
    $('#dl-menu ul,#dl-menu li').removeClass('nav-bar-desctop dropdown dropdown-menu');
    $('#dl-menu > ul').addClass('dl-menu').find('li >ul').addClass('dl-submenu');

    // for top menu
    $('#dl-menu').dlmenu({
        animationClasses: {classin: 'dl-animate-in-2', classout: 'dl-animate-out-2'},
        useActiveItemAsBackLabel: true,
        useActiveItemAsLink: true,
    });

    //button open for top menu
    $('#main-header .mobile-menu .dl-trigger').on('click', function () {
        if (!$(this).parent().find('.dl-menuwrapper .dl-menu').hasClass('dl-menuopen')) {
            $('#dl-menu').dlmenu("openMenu");
            $(this).parent().find('.dl-menuwrapper').css({"height": '100%'});
            $(this).addClass('dl-active');
        } else {
            $(this).parent().find('.dl-menuwrapper').css({"height": '0'});
            $('#dl-menu').dlmenu("closeMenu");
            $(this).removeClass('dl-active');
        }
        return false;
    });

    //click on the other place exept the button
    $('#dl-menu').on('click', function () {
        $('.dl-menuwrapper').css({"height": '0'});
        $('#dl-menu').dlmenu("closeMenu");
        $('#main-header .mobile-menu .dl-trigger').removeClass('dl-active');
    });

    $('#main-header .mobile-menu li.active').parents('li').find('>a').click();
    $('#main-header nav .nav-bar-desctop > li.dropdown').hover(function () {
        $(this).find('ul.dropdown-menu').fadeIn(400);
    }, function () {
        $(this).find('ul.dropdown-menu').fadeOut(400);
    });

    //scroll and search box ---------------------------------------------------------
    function fixedTopNav() {
        if ($(window).scrollTop() > 1) {
            $('#main-header').addClass('fixed-block');
        } else {
            $('#main-header').removeClass('fixed-block');
        }
        if ($(window).outerWidth() > 992) {
            $('#main-header').removeClass('fixed-block');
        }
    }

    fixedTopNav();

    $(window).on("scroll", function () {
        fixedTopNav();
    });

    $('#main-header .wrap-mobile-not-search .show-search-box').click(function (e) {
        e.preventDefault();
        $('#main-header').addClass('searchbox-open');
    });

    $('#main-header .search-mobile-version .close-search-box').click(function (e) {
        e.preventDefault();
        $('#main-header').removeClass('searchbox-open');
    });

    $(window).resize(function () {
        if ($(window).outerWidth() > 768) {
            $('#main-header').removeClass('searchbox-open');
        }
    });

    /*----for admin panel--------*/
    //init toast notification options
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "500",
        "timeOut": "3500",
        "extendedTimeOut": "500",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    function confirmDeletion(a) {
        1 == confirm("Do you want to delete this record?") && window.location.replace(a)
    }

    $(document).ready(function () {
        var a = $(".admin-navbars .navbars-wrapper");
        a.on("click", ".show-panel", function (b) {
            b.preventDefault();
            var c = $(this).attr("href");
            $(c).hasClass("admin-sidebar-right") && ($(".admin-navbars .navbars-wrapper .admin-sidebar-left").hasClass("open") && $(".admin-navbars .navbars-wrapper .admin-sidebar-left").toggleClass("open"), a.toggleClass("right-open"), $(c).toggleClass("open")), $(c).hasClass("admin-sidebar-left") && ($(".admin-navbars .navbars-wrapper .admin-sidebar-right").hasClass("open") && ($(".admin-navbars .navbars-wrapper .admin-sidebar-right").toggleClass("open"), a.toggleClass("right-open")), $(c).toggleClass("open"))
        }), $("#cancel-changes-saving").on("click", function () {
            $("#adminSidebarLeft").toggleClass("open")
        }), $("#cancel-changes-rejecting").on("click", function () {
            $("#adminSidebarLeftReject").toggleClass("open")
        }), $(window).scrollTop() > 1 ? $(".admin-panel").addClass("fixed-nav") : $(".admin-panel").removeClass("fixed-nav"), $(window).on("scroll", function () {
            $(window).scrollTop() > 1 ? $(".admin-panel").addClass("fixed-nav") : $(".admin-panel").removeClass("fixed-nav")
        }), $(window).scrollTop() > 1 ? $(".admin-panel .admin-navbars").addClass("fixed-bars") : $(".admin-panel .admin-navbars").removeClass("fixed-bars"), $(window).on("scroll", function () {
            $(window).scrollTop() > 1 ? $(".admin-panel .admin-navbars").addClass("fixed-bars") : $(".admin-panel .admin-navbars").removeClass("fixed-bars")
        })
    });

    //initial widgets array
    window.widgets = [];
    var inline = false;
    var comments = $('#system_page-version-comment');
    var commentsText = '';
    comments.on('input', function () {
        commentsText = $(this).val();
    });

    var rejectComments = $('#system_page-reject-version-comment'),
        rejectCommentsText = '';
    rejectComments.on('input', function () {
        rejectCommentsText = $(this).val();
    });

    // TODO: Move this functions from FCR Theme. It must to be a global in PageBundle
    $("#save-page-changes").on('click', function () {
        if (window.widgets.length == 0) {
            toastr.warning("No changes for saving");
        } else {
            $.ajax({
                type: "POST",
                url: '/ajax/page/editor',
                data: {
                    page: {
                        action: 'savePageWidgetContent',
                        id: $('#system_page-id').val(), // TODO: Provide Dynamic Page ID
                        widgets: window.widgets,
                        author: $('#system_user-id').val(), // TODO: Provide Dynamic User/Author ID
                        versionComment: commentsText //TODO: Enable Version Comment!
                    }
                },
                dataType: "json",
                beforeSend: function () {
                    pageAjaxLoader.show();
                },
                success: function (response) {
                    pageAjaxLoader.hide();
                    window.widgets = [];
                    toastr.success(response.message);
                    switchInline();
                    $("#adminSidebarLeft").toggleClass("open");
                    $("#edit-icon").removeClass('active');
                    $("#send-to-approval").show();
                },
            });
        }
    });

    $("#reject-page-changes").on('click', function () {
        var url = $(this).data('url');

        $.ajax({
            type: "GET",
            url: url,
            data: {versionComment: rejectCommentsText},
            beforeSend: function () {
                pageAjaxLoader.show();
            },
            success: function () {
                pageAjaxLoader.hide();
                toastr.success('Succesfully rejected!');
                $("#adminSidebarLeftReject").toggleClass("open");
                location.reload();
            },
        });
    });

    var mode = $('#mode-status').data('mode');


    // Set inline-editable
    // TODO: Move to react
    var inline = false;
    var pageStatus = $('.label.page-status').text();

    if (mode == 'edit' && pageStatus == 'draft') {
        switchInline();
        $(this).toggleClass('active');
    }

    function switchInline() {
        inline = !inline;
        $('[contenteditable]').attr('contenteditable', inline);
        $(".inline-mode").toggleClass('inline-highlight');
        $(".cfg-tool").toggle('cfg-tool');
    };

    $('ul.icheck input').iCheck({
        checkboxClass: 'icheckbox_polaris',
        radioClass: 'iradio_polaris',
    });

    // ===== Scroll to Top ====
    $(window).scroll(function () {
        if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
            $('#return-to-top').fadeIn(200);    // Fade in the arrow
        } else {
            $('#return-to-top').fadeOut(200);   // Else fade out the arrow
        }
    });

    $('#return-to-top').click(function () {      // When arrow is clicked
        $('body,html').animate({
            scrollTop: 0                       // Scroll to top of body
        }, 500);
    });

    //slick for widget Feature Slider
    $('.feature-slider-js,.slider-info-js').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: true,
        dots: true,
        adaptiveHeight: true
    });

    //accordion for widget---------------------
    $('.accordionElement .panel-heading').on('click', function () {
        $('.accordionElement .panel-heading').removeClass('active');
        $(this).addClass('active');
    })

    //=====VideoPlayer======
    function heroVideoInit(a, b, c, d) {
        a || b || c || d || (a = "hero-video", b = "video", c = "playVideo", d = "hero-image");
        var e = $("#" + a);
        if (e.length) {
            var f = $("html, body"), g = $("." + b), h = $(window).height(), i = new Vimeo.Player(e),
                j = $("." + d).offset().top;
            j = j > 0 ? j : 0, e.css("height", h), g.css("top", 0), $("." + c).click(function () {
                f.animate({scrollTop: j}, 500).css("overflow", "hidden"), g.show(), i.play().then(function () {
                }).catch(function (a) {
                    a.name
                })
            }), $("." + b + " .closeVideo").click(function () {
                i.pause().then(function () {
                }).catch(function (a) {
                    a.name
                }), g.hide(), f.css("overflow", "").animate({scrollTop: j}, 100)
            })
        }
    }

    heroVideoInit();
    initVideos();

    function initVideos() {
        $('.video-widget').each(function () {
            var id = this.id;
            heroVideoInit(id, 'video' + id, 'playVideo' + id, 'col-arts')
        });
    }

    $(window).onresize = function () {
        heroVideoInit();
        initVideos();
    }

    //-------------------stock price------------------
    $.stokePriceWidget = function (mainPage) {
        var investorsStockValueAPI = "/investors/stock-value"

        function fillDataYFW(d) {
            //set Last Trade Price
            //For Main page
            $(".cta-box-finance .ind").html('$' + d.lastPrice);

            var col3Value = d.changeValue.replace('+', '+$');
            var col4Value = d.dividend;
            if (col3Value.charAt(0) == '-') {
                col3Value = "($" + d.changeValue.replace('-', '') + ")";
            } else {
                col3Value = "($" + d.changeValue + ")";
            }
            if (col4Value.charAt(0) == '-') {
                col4Value = "(" + d.dividend.replace('-', '') + ")";
            } else {
                col4Value = "(" + d.dividend + ")";
            }

            //set P/E Ratio
            if (parseFloat(d.changeValue) < 0) {
                $(".cta-box-finance .arrow").addClass('pink-arrow').html('<i class="fa fa-angle-down" aria-hidden="true"></i>');
                $(".cta-box-finance .usd").addClass('pink-arrow').html(col3Value + "<br/>" + col4Value);
            } else {
                $(".cta-box-finance .arrow").removeClass('pink-arrow').html('<i class="fa fa-angle-up" aria-hidden="true"></i>');
                $(".cta-box-finance .usd").removeClass('pink-arrow').html(col3Value + "<br/> " + col4Value);
            }

            $(".cta-box-finance .date").html("TSX:FCR <span>as of " + moment(d.lastTrade.date).format('DD/MM/YYYY h:mma') + " " + " EDT</span>");

            //For IR page
            if ($(".finance-block").length > 0) {
                $(".finance-block .ind_value").html('$' + d.lastPrice);
                if (parseFloat(d.changeValue) < 0) {
                    $(".finance-block .statArrow").addClass('pink-arrow').html('<i class="fa fa-angle-down" aria-hidden="true"></i>');
                    $(".finance-block .usd").addClass('pink-arrow').html(col3Value + "<br/>" + col4Value);
                } else {
                    $(".finance-block .statArrow").removeClass('pink-arrow').html('<i class="fa fa-angle-up" aria-hidden="true"></i>');
                    $(".finance-block .usd").removeClass('pink-arrow').html(col3Value + "<br/> " + col4Value);
                }
                $(".finance-block .date").html("TSX:FCR <span>as of " + moment(d.lastTrade.date).format('DD/MM/YYYY h:mma') + " " + " EDT</span>");
            }
        }

        function fillDataYFWQuoteChart(d) {
            //set Last Trade Price
            $(".stock-price .ind").html('$' + d.lastPrice);

            var col3Value = d.changeValue.replace('+', '+$');
            var col4Value = d.dividend;
            if (col3Value.charAt(0) == '-') {
                col3Value = "($" + d.changeValue.replace('-', '') + ")";
            }
            if (col4Value.charAt(0) == '-') {
                col4Value = "(" + d.dividend.replace('-', '') + ")";
            }

            //set P/E Ratio
            if (parseFloat(d.changeValue) < 0) {
                $(".stock-price .arrow").addClass('pink-arrow').html('<i class="fa fa-angle-down" aria-hidden="true"></i>');
                $(".stock-price .usd").addClass('pink-arrow').html(col3Value + " " + col4Value);
            } else {
                $(".stock-price .arrow").removeClass('pink-arrow').html('<i class="fa fa-angle-up" aria-hidden="true"></i>');
                $(".stock-price .usd").removeClass('pink-arrow').html(col3Value + " " + col4Value);
            }
            $(".stock-price .date").html("As of " + moment(d.lastTrade.date).format('DD/MM/YYYY h:mma') + " " + " EDT");

            //set volume
            var td = $('.stock-price td:odd');
            var volume = d.volume;
            volume = volume.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1,');
            $(td[0]).html(volume);
            //set 52 Week High
            $(td[1]).html(d.yearHigh);
            //set 52 Week Low
            $(td[2]).html(d.yearLow);
        }

        $.get(investorsStockValueAPI, function () {
        })
            .done(function (data) {
                if (data.success == true && data.data[0]) {
                    var d = data.data[0];
                    if (mainPage) {
                        fillDataYFW(d);
                    } else {
                        fillDataYFWQuoteChart(d);
                    }

                    $('.loader-gif').hide();
                } else {
                    console.warn("Data is empty. Need import stock values for Stock widget")
                }
            })
            .fail(function () {
                console.log("Something wrong. Data doesn't load")
            });
    };
    $.stokePriceWidget(true);
    //-------------------END stock price------------------

    //button open/close all
    $('#collapse-init').on('click', function () {
        if ($(this).hasClass('expand-all')) {
            $(this).parents('.user-accordion').find('.panel-collapse').slideDown(300).removeClass('collapse');
            $(this).parents('.user-accordion').find('.panel-title .accordion-toggle').removeClass('collapsed');
            $(this).removeClass('expand-all').addClass('close-all').text('CLOSE ALL -');
        } else {
            $(this).parents('.user-accordion').find('.panel-collapse').slideUp(300).addClass('collapse');
            $(this).parents('.user-accordion').find('.panel-title .accordion-toggle').addClass('collapsed');
            $(this).addClass('expand-all').removeClass('close-all').text('EXPAND ALL +');
        }
    });

    //click elements
    $('.user-accordion .panel-title ').on('click', function (e) {
        e.preventDefault();

        if ($(this).find('.accordion-toggle').hasClass('collapsed')) {
            $(this).parents('.user-accordion').find('.panel-collapse').slideUp(300).addClass('collapse');
            $(this).parents('.user-accordion').find('.panel-title .accordion-toggle').addClass('collapsed');

            $(this).find('.accordion-toggle')
                .removeClass('collapsed')
                .parents('.panel-default')
                .find('.panel-collapse')
                .slideDown(300)
                .removeClass('collapse');
        } else {
            $(this).find('.accordion-toggle')
                .addClass('collapsed')
                .parents('.panel-default')
                .find('.panel-collapse')
                .slideUp(300)
                .addClass('collapse');
        }
    });
    /* END Accordion */

    /*PAGE RETAIL LIFE*/
    $(window).on('resize.Retail', resizeRetailArt);

    function resizeRetailArt() {
        var winw = screen.width;
        if (winw <= 1025) {
            $('.isotope-masonry-gallery .grid-item--1w').css("min-width", winw)
        } else {
            $('.isotope-masonry-gallery .grid-item--1w').css("min-width", '')
        }
    }

    resizeRetailArt();

    setTimeout(function () {
        $('.isotope-masonry-gallery').isotope({
            itemSelector: '.grid-item',
            masonry: {
                columnWidth: 1,
            },
        });
    }, 1000);

    $('.arts-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        adaptiveHeight: true
    });
    /*PAGE RETAIL LIFE*/

    $.validator.addMethod('require-one', function (value, element) {
            return $('.icheck  .require-one:checked').size() > 0;
        },
        'Please select at least one newsletter category.'
    );
    var checkboxes = $('.require-one');
    var checkbox_names = $.map(checkboxes, function (e, i) {
        return $(e).attr("name")
    }).join(" ");

    $('.subscribe-form').each(function (index) {
        var formID = $(this).attr('id');
        formID += '-' + index;

        $(this).attr('id', formID);

        $(this).find('input').each(function () {
            var inputId = $(this).attr('id');
            inputId += '-' + index;
            $(this).attr('id', inputId);
        });

        $('#' + formID).validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: "Please enter a valid email address"
            },
            groups: {checks: checkbox_names},
            errorPlacement: function (a, b) {
                $(".icheck .form-group .checkbox label").on('click', function () {
                    if ($('.icheck .require-one:checked').size() > 0) {
                        $('#checks-error').hide();
                    } else {
                        $('#checks-error').show();
                    }
                })
                if ($(b).parent().attr('class') == "icheckbox_polaris") {
                    a.insertAfter($(".icheck"))
                } else {
                    a.insertAfter($(b).parent());
                }
            }, submitHandler: function (a) {
                $('#' + formID).prepend('<img class="loader-gif" src="https://fcr.ca/images/squares.gif">');
                $.post($(a).attr('action'), $(a).serialize()).done(function (b) {
                    $('#' + formID + ' .loader-gif').remove();
                    b.success && (a.reset(), $('#' + formID + ' .message').addClass("alert-success"), $('#' + formID + ' .message').html(b.message))
                })
            }
        });
    });

    var unsubscribeWindow = $("#unsubscribeLink").fancybox();
    $("#unsubscribeForm").on("submit", function (a) {
        a.preventDefault(), $("#unsubscribeForm .message").removeClass("alert-success"), $("#unsubscribeForm .message").removeClass("alert-warning"), $("#unsubscribeForm .message").empty();
        var b = this;
        $.get("/newsletter/unsubscribe", $(b).serialize())
            .done(function (a) {
                a.success ? (b.reset(), $("#unsubscribeForm .message").addClass("alert-success"), $("#unsubscribeForm .message").html(a.message)) : ($("#unsubscribeForm .message").addClass("alert-warning"), $("#unsubscribeForm .message").html(a.message))
            })
    }),
    window.location.href.indexOf("unsubscribeLink") > -1 && setTimeout(function () {
        $("#unsubscribeLink").click()
    }, 300);

    //Elfinder dialog window for LinkIconListWidget
    $(document).ready(function () {
        pageAjaxLoader.hide();

        $(".open-elfinder").each(function () {
            $(this).click(function (event) {
                event.preventDefault();
                var id = $(this).attr("id");
                elfinderDialog(id);
            })
        })

        function elfinderDialog(id) {
            var elfinder = $('#linkIconListElfinder').elfinder({
                url: '/assets/elfinder/php/connector.php',
                resizable: false,
                getfile: {
                    onlyURL: true,
                    multiple: false,
                    folders: false,
                    oncomplete: ''
                },
                handlers: {
                    dblclick: function (event, elfinderInstance) {
                        var el = $('#linkFilePath' + id);
                        var fileInfo = elfinderInstance.file(event.data.file);
                        if (fileInfo.mime != 'directory') {
                            el.html('');
                            el.trigger("focus")
                            document.execCommand('insertText', false, elfinderInstance.url(event.data.file))
                            elfinderInstance.destroy();
                            return false;
                        }
                    },
                    destroy: function (event, elfinderInstance) {
                        elfinder.dialog('close');
                    },
                }
            }).dialog({
                title: 'filemanager',
                resizable: true,
                width: 920,
                height: 500
            });
        }
    })

    var pageAjaxLoader = {
        show: function () {
            $('.loading').show();
        },
        hide: function () {
            $('.loading').hide();
        }
    }
});
