$(document).ready(function(){
    //Utility funcs
    $.fn.goTo = function () {
        return $("html, body").animate({scrollTop: $(this).offset().top + "px"}, "fast"), this
    }
    //end
    $(".accordionElement .panel-title").on('click', function() {

        $('.accordionElement .panel-heading').removeClass('active');

        if ( $(this).find('a').hasClass('collapsed') ) {
            $(this).parent().addClass('active');
            // Show all the properties filter
        } else {
            $(this).parent().removeClass('active');
        }

    });

    var url = document.location.hash.toString();
    if ( url == '#show-AB' ) {
        $('#collapse-AB').addClass('in');
    }
    if ( url == '#show-BC' ) {
        $('#collapse-BC').addClass('in');
    }
    if ( url == '#show-ON' ) {
        $('#collapse-ON').addClass('in');
    }
    if ( url == '#show-QC' ) {
        $('#collapse-QC').addClass('in');
    }

    $(".properies-listview").hide();

    $('.switch-to-grid').on('click', function() {
        $(".properies-listview").hide();
        $(".properies-gridview").show();
    });

    $('.switch-to-list').on('click', function() {
        $(".properies-listview").show();
        $(".properies-gridview").hide();
    });

    var table = $('#dataTable-AB,' +
        '#dataTable-BC,' +
        '#dataTable-ON,' +
        '#dataTable-QC')
        .DataTable({
            "sDom": '<"top"><"bottom">',
            "searching": true,
            "paging": false,
            "info": false,
            "columnDefs": [
                {
                    "targets": [ 2 ],
                    // "visible": false,
                    "searching": true,
                }
            ]
        });
    /* Custom filtering function which will search data by type */

    $('.table-filter').on( 'click', function () {
        var type = $(this).attr('filter');
        table.search( type )
            .draw();
    } );

    $('.filters .wrapper-mobile-filter .active-item').text($('.filters .wrapper-mobile-filter ul li:first').text());

    $('.filters .wrapper-mobile-filter .active-item').click(function(){
        if ($(this).parent().hasClass('open-state')){
            $(this).parent().removeClass('open-state');
        } else {
            $(this).parent().addClass('open-state');
        }
    });

    $('.filters .wrapper-mobile-filter ul li a').on('click',function(){
        var offset = $('.properies-gridview').offset();
        //$(window).scrollTop((offset.top-130));
        var LinkText = $(this).text();
        var $wpapFilter = $(this).parents('.wrapper-mobile-filter');
        $wpapFilter.find('.active-item').text(LinkText);
        if ($wpapFilter.hasClass('open-state')){
            $wpapFilter.removeClass('open-state');
        } else {
            $wpapFilter.addClass('open-state');
        }
    });

    $(window).scroll(function() {
        var offset = $('.grid').offset(),
            bottom = $('.grid').outerHeight() + offset.top;

        // If page is scrolled more than 50px
        if ($(this).scrollTop() >= (offset.top-60) ) {
            $('.grid .properies-gridview').addClass('fixed-block');
        } else {
            $('.grid .properies-gridview').removeClass('fixed-block');
        }
        if ($(this).scrollTop() >= bottom) {
            $('.grid .properies-gridview').removeClass('fixed-block');
        }
    });

    var added = false,
        fmode = false,
        province = '',
        filter = '';

    //return user view to start of panel
    $(".accordionElement .panel-heading").on('click', function() {
        var id = $(this).attr("id");
        filter = '';
        fmode = false;
        infinityLoad(filter);
        setTimeout(function() {
            $("#collapse-" + id).goTo();
        }, 500)
    });

    //redraw grid or list with filter
    $(".filter").on("click", function (e) {
        filter = $(this).attr("filter")
        fmode = true;
        var propsContainer = $(".properies-gridview" + "." + province).children(".properties").children(".row");
        propsContainer.children(".col-lg-3.col-md-3").remove();
        //infinityLoad(filter);
        generateItems(province, filter)
    })
    $("#more-ab, #more-bc, #more-on, #more-qc").on("click", function() {
        var pr = $(this).attr("id").slice(-2);
        generateItems(pr, filter)
    })

    var infinityLoad = function(filter) {
        if (!fmode) {
            $(".properies-gridview").on(
                "mouseover touchstart",
                function (e) {
                    Infinite(e, filter)
                })
        } else {
            $(".properies-gridview").off();

            $(".properies-gridview").on(
                "mouseover touchstart",
                function (e) {
                    Infinite(e, filter)
                })
            generateItems(province, filter)
        }
    }
    //infinityLoad(filter);

    // SimpleInfiniteScroll
    function Infinite(e, f){
        var target = $(e.target),
            gridview = target.parents(".properies-gridview"),
            grid = gridview.parents(".grid");

        if (gridview) {
            province = gridview.attr("name");
            /*var wrapH = grid.height();
             if (window.innerWidth > 760) {
             if (e.type != 'touchstart') {
             if (grid.offset() == undefined) return false;
             if ((e.pageY - grid.offset().top) > wrapH * 0.6 && !added) {
             added = true;
             generateItems(province, f)
             }
             } else {
             if (e.originalEvent.touches[0].pageY > wrapH * 0.6) {
             generateItems(province, f)
             }
             }
             }*/
        }
    }

    function generateItems (province, filter) {

        var propsContainer = $(".properies-gridview" + "." + province).children(".properties").children(".row"),
            load_more = $("#more-"+ province +" .more-props"),
            newItems = '';
        var items_length = propsContainer.children().children(".property-item").length;
        var offset = (items_length == 0) ? 0 : items_length;
        var options = {
            getProperties : true,
            province : province,
            offset : offset,
            limit : 12,
            filter : filter,
            js : true
        }

        $.when(getData(options)).done(function(d) {
            var data = d.data;
            //clear images for only new zooming
            $('[name = ' + options.province +'] .zoom.new').removeClass( "new");
            // if all properties loaded stop send requests

            $.each(data, function (i, prop) {
                // alert(prop.sqft)
                if($.isNumeric(i)) {
                    var sq = prop.sqft,
                        sq_arr = sq.split(' '),
                        last = sq_arr[sq_arr.length - 1],
                        s;
                    if (sq.indexOf(' ') !== -1) {
                        s = last
                    }
                    else {
                        // Round to nearest 50
                        var sqft = Math.ceil(prop.sqft / 50) * 50;
                        s = Number(sqft).toFixed().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }
                    // var thumbnail = prop.thumbnail;
                    var defaultThumbnail = 'https://fcr.ca/uploads/properties/thumbs/temp_property-image_400x400.jpg',
                        thumbnail = (prop.thumbnail == "NULL") ? defaultThumbnail : prop.thumbnail,
                        title = prop.parentName,
                        address = (prop.geoAddress1 != null ? prop.geoAddress1 : '') + (prop.geoAddress2 != null ? prop.geoAddress2 : ''),
                        city = prop.geoCity;
                    var item =
                        '<div class="col-lg-3 col-md-3 col-sm-6 allow-filtering ' + prop.filter + '">' +
                        '<div class="property-item center-block">' +
                        '<a class="zoom new" href="/portfolio-leasing/view/' + prop.code + '" target="_blank">' +
                        '<img class="img-responsive"' +
                        'src="' + thumbnail + '"' +
                        'alt="Image"/>' +
                        '<div class="description-wrapper">' +
                        '<span><strong>' + title + '</strong></span> <br />' +
                        '<span><small>' + address + '</small></span> <br>' +
                        '<span><small>' + city + '</small></span><br>' +
                        '<span><small>' + s + ' ' + TRANSLATIONS.approximate_sq_ft + '</small></span>' +
                        '</div>' +
                        '</a>' +
                        '</div>' +
                        '</div>';
                    newItems += item;
                }
            });

            if(data.no_more) {
                load_more.hide();
            } else {
                load_more.show();
            }

            var props = $(newItems).hide()
            //propsContainer.append( props )
            props.insertBefore("#more-" + options.province);
            props.show('normal');
            added = false;
            zoomingImg(options.province);
        });
    }

    zoomingImg(null);

    function zoomingImg (province) {
        var images;
        if (parseInt($(window).outerWidth()) > 480) {
            if(province !== null) {
                images = $('[name = ' + province +'] .zoom.new img')
                images.trigger('zoom.destroy');
            } else {
                images = $('.zoom img');
            }
            images
                .wrap('<span style="display:inline-block"></span>')
                .css('display', 'block')
                .parent()
                .zoom();
        }
    }

    function getData(options) {
        return  $.ajax({
            url: "/portfolio-leasing/map-data/getProperties",
            type: "GET",
            data:{
                getProperties : options.getProperties,
                province : options.province,
                offset : options.offset,
                limit : options.limit,
                filter : options.filter,
                js : options.js
            },
            success: function(response) {
                return JSON.stringify(response.data);
            },
            error: function(xhr) {
                return xhr.status;
            }
        });
    }
    //END filter grid

    //Mobile menu fix for portfolio-leasing only
    $(window).on("scroll", function() {
        if ($(window).scrollTop() > 1) {
            if (window.innerWidth < 1024){
                $('#mapCanada svg').css('margin-top', '60px')
            }
            $('#main-header').addClass('fixed-block');
        } else {
            if (window.innerWidth < 760) {
                $('#mapCanada svg').css('margin-top', '0')
            }
            $('#main-header').removeClass('fixed-block');
        }
    });


    //Leasing vector Map
    $(".nav-bar-desctop li").mouseover(function() {
        if($(this).children("ul").length) $(".backToMap").hide();
        $(this).mouseout(function() {
            $(".backToMap").show()
        })
    })

    d3.select(window).on('resize', resize);
    renderMap();
    function resize() {
        $('#mapCanada svg').remove();
        $(".backToMap, .backMap, .nextMap").addClass("hidden");
        renderMap();
    }
});

var TRANSLATIONS = window.portfolioPageTranslations;

function renderMap() {
    var width = window.innerWidth,
        height = window.innerHeight,
        ratio = width/height,
        height = ratio > 1 ? height * ratio/2 : height * ratio *.8,
        active = d3.select(null),
        zoom = false,
        gmapNode = d3.select("#googleMap").node(),
        all_properties = {};

    //maphead div mobile view fix
    if(width >= 1024 && ratio > 1) {
        $('.maphead').removeClass("hidden");
    }else {
        $('.maphead').addClass("hidden");
        $("#googleMap").css("height","100%")
    }

    var container = d3.select('#mapCanada').node(),
        data = container.dataset;

    var proj = d3.geoAlbers()
            .parallels([70, 45])
            .translate([width/2 , height])
            //for desktop and tablet landscape
            .scale(ratio > 1 && width > 760 ? (width - 320) :
                //for tablet portrait
                (width > 760) ? 1000 * ratio - 300 + (width - 320) :
                    //for mobile landscape
                    ratio > 1 ? width - 200 :
                        //for mobile portrait
                        1000 * ratio - 200 + (width - 320)),

        path = d3.geoPath().projection(proj);

    var svg = d3.select(container).append("svg")
        .attr("width", width)
        .attr("height", height)
        .attr("class", "graph-svg-component");

    d3.json(data.provinces, renderProvinces);


    function renderProvinces(canada) {
        //remove loading block and fade-in title for styling issues.
        $(".loader").hide();
        $(".map-tittle").fadeIn(200);
        //Load provinces from json
        var prjson = topojson.feature(canada, canada.objects.provinces)

        $.getJSON("/portfolio-leasing/map-data/active_provinces", function(data) {
            var data = data.data;
            //Get all properties once, set number of props for every province
            $.getJSON("/portfolio-leasing/map-data/properties", function(data_props) {
                all_properties = data_props.data;
                var pcodes = [],
                    propsNumber = {};
                for (var i = 0; i < data.length; i++) {
                    pcodes.push(data[i].province);
                    propsNumber[data[i].province] = 0;
                }
                for (city in all_properties) {
                    var city = all_properties[city];
                    for (var i = 0; i < city.length; i++) {
                        if (pcodes.indexOf(city[i].province) > -1) {
                            propsNumber[city[i].province] += 1
                        }
                    }
                }
                // Grab Province Name and set Act Prov Array
                var actProvinces = [];
                for (var i = 0; i < data.length; i++) {

                    var dataState = data[i].province;

                    // Find the corresponding province inside the GeoJSON
                    // Copy the data value into the JSON if active
                    for (var j = 0; j < prjson.features.length; j++) {
                        var jsonState = prjson.features[j].properties.PREABBR;
                        if (dataState == jsonState) {
                            prjson.features[j].properties.active = 1;
                            actProvinces.push(dataState);
                            break;
                        }
                    }
                }

                var provinces = svg.append("g")
                    .attr("class", "provinces")
                    .selectAll("path")
                    .data(prjson.features)
                    .enter().append("path")
                    .attr("d", path)
                    .attr("id", function (d) {
                        return d.properties.PREABBR;
                    })
                    .attr("transform", "translate(0,0)" + "scale(1)")
                    .attr("class", function (d) {
                        var value = d.properties.active;
                        if (value) {
                            return "province-active";
                        } else {
                            return "province";
                        }
                    })
                    .on("click", clicked);

                var texts = svg.selectAll("text")
                    .data(data)
                    .enter();

                texts.append("text")
                    .text(function(d){
                        return (width >= 768) ? d.name : d.province;})
                    .attr("x", function (d) {
                        return proj([d.lablat, d.lablong])[0] - this.getComputedTextLength()/3;
                    })
                    .attr("y", function (d) {
                        return proj([d.lablat, d.lablong])[1];
                    })
                    .attr("dy", "0.5em")
                    .classed("province-label-text", "true");

                texts.append("text")
                    .text(function(d){return propsNumber[d.province] + " " + TRANSLATIONS.properties;})
                    .attr("x", function (d) {
                        return proj([d.lablat, d.lablong])[0] - this.getComputedTextLength()/3;
                    })
                    .attr("y", function (d) {
                        return proj([d.lablat, d.lablong])[1] + 5;
                    })
                    .attr("dy", (width >= 768) ? "1.5em" : "1em")
                    .classed("small-labl-text", "true")
                    .classed("province-label-text", "true");

                var zoomRate = 10;
                var regionClasterize = function () {
                    $.getJSON("/portfolio-leasing/map-data/cities", function(data) {
                        var data = data.data;
                        //Sort data that least places value cities have been appended  last in
                        data.sort(function (a,b) {
                            return b.places - a.places;
                        })

                        //Set Shadow Filter
                        var defs = svg.append("defs");

                        var filter = defs.append("filter")
                            .attr("id", "dropshadow")
                            .attr('filterUnits', "userSpaceOnUse")
                            .attr('width', '150%')
                            .attr('height', '150%');

                        filter.append("feGaussianBlur")
                            .attr("in", "SourceAlpha")
                            .attr("stdDeviation", 1)
                            .attr("result", "blur-out");
                        filter.append("feColorMatrix")
                            .attr('in', 'blur-out')
                            .attr('type', 'hueRotate')
                            .attr('values', 180)
                            .attr('result', 'color-out');
                        filter.append("feOffset")
                            .attr("in", "color-out")
                            .attr("dx", -2)
                            .attr("dy", 2)
                            .attr("result", "the-shadow");
                        filter.append("feBlend")
                            .attr('in', 'SourceGraphic')
                            .attr('in2', 'the-shadow')
                            .attr('mode', 'normal');

                        //Render Cities Marker
                        data.forEach(function (d) {
                            d.x = proj([d.lng, d.lat])[0];
                            d.y = proj([d.lng, d.lat])[1];
                            d.r = Math.sqrt(d.places) * 3 * ((width < 760) ? (ratio < 1) ? ratio :
                                        //for mobile/tablets landscape
                                        (ratio > 1) ? 0.5 :
                                            1 :
                                    //for desktop > 1600
                                    width >= 1600 ? 1*ratio :
                                        //for desktop < 1600
                                        1),
                                d.w = 1;
                        })
                        svg.selectAll("circle:not(.province-label-circles)")
                            .data(data)
                            .enter()
                            .append("circle")
                            .attr("cx", function (d) { return d.x; })
                            .attr("cy", function (d) { return d.y; })
                            .attr("r", function (d) { return d.r; })
                            .attr("province", function (d) {
                                return d.place;
                            })
                            .classed('provinceCluster', function (d) {
                                return d.provinceCluster == 1;
                            })
                            .classed('citiesCluster', function (d) {
                                return d.citiesCluster == 1;
                            })
                            .classed('hidden', function (d) {
                                return d3.select(this).classed("citiesCluster")
                            })
                            .attr("filter", "url(#dropshadow)")
                            .on("click",function (d) {
                                var path = d3.select("#" + d.place);
                                if(!zoom){
                                    path.on('click').call(path.node(), path.datum());
                                }else {
                                    d3.select("#googleMap").classed("hidden", false);
                                    googleMap(d.lat, d.lng, d.zoom);
                                }
                            })
                            .append("title")
                            .text(function (d) {
                                if (d.city !== '') {
                                    return d.city + ' ' + d.places + ' ' + TRANSLATIONS.properties;
                                } else {
                                    return d.places + ' ' + TRANSLATIONS.properties;
                                }
                            });

                        var regionClusters = [];
                        function merge() {
                            data.forEach(function(d,i) {
                                if (d.w == 0) return;
                                data.slice(0, i).forEach(function(e) {
                                    if (e.w == 0 || d.place != e.place || e.city == "") return false;
                                    var cur = d,
                                        nxt = e,
                                        r2 = cur.r + nxt.r,
                                        x1 = cur.x,
                                        x2 = nxt.x,
                                        y1 = cur.y,
                                        y2 = nxt.y,
                                        dr = Math.sqrt(Math.pow((x2 - x1),2) + Math.pow((y2 - y1),2));
                                    if (dr < r2) {
                                        if (cur.r >= nxt.r) {
                                            e.w = 0;
                                        } else {
                                            if (regionClusters.indexOf(e) == -1) {
                                                regionClusters.push(e);
                                            }
                                            d.w = 0;
                                        }
                                    }
                                });
                            });
                        }
                        merge();



                        /*function zoomCluster(d) {
                         inZoomRegion = true;
                         var xmin = d.x - d.r,
                         ymin = d.y + d.r,
                         xmax = d.x + d.r,
                         ymax = d.y - d.r,
                         dx = xmax - xmin,
                         dy = ymax - ymin,
                         x = (xmin + xmax) / 2,
                         y = (ymin + ymax) / 2,
                         // * 1.1 - fix for markers not cropped in bounds
                         scale = .51 / Math.max(dx / width * 1.1, dy / height * 1.1),
                         translate = [width / 2 - scale * x, height / 2 - scale * y];

                         var prov = d.place,
                         citiesCluster = d3.selectAll("circle")
                         .filter(".citiesCluster")
                         .filter(function () {
                         return d3.select(this).attr("province") == prov
                         }),
                         regionCluster = d3.selectAll("circle")
                         .filter(".regionCluster")
                         .filter(function (d) {
                         return d3.select(this).attr("province") == prov
                         });

                         citiesCluster.each(function (d) {
                         var self = d3.select(this);
                         self.attr("r", d.r / zoomRate)
                         .classed("hidden", false)
                         });

                         citiesCluster.transition()
                         .duration(750)
                         .attr("transform", "translate( " + translate[0] + "," +
                         translate[1] +
                         " ),scale(" + scale + ")");

                         regionCluster
                         .classed("hidden", true);

                         provinces.transition()
                         .duration(750)
                         .attr("transform", "translate(" + translate + ")scale(" + scale + ")");

                         }*/

                        svg.selectAll("regionCluster")
                            .data(regionClusters)
                            .enter()
                            .append("circle")
                            .attr("cx", function (d) { return d.x; })
                            .attr("cy", function (d) { return d.y; })
                            .attr("r", function (d) { return d.r; })
                            .attr("province", function (d) {
                                return d.place;
                            })
                            .classed("regionCluster", true)
                            .classed('hidden', true)
                            //.attr("filter", "url(#dropshadow)")
                            .on("click", function (d) {
                                var path = d3.select("#" + d.place);
                                if(!zoom){
                                    path.on('click').call(path.node(), path.datum());
                                }else {
                                    d3.select("#googleMap").classed("hidden", false);
                                    googleMap(d.lat, d.lng, d.zoom);
                                }
                            });

                        var cCities = svg.selectAll(".citiesCluster");

                        cCities.filter(function(d){ return !d.w })
                            .classed("hidden", true)
                        cCities.filter(function(d){ return d.w })
                            .each(function (d){
                                d.old = 1
                            })
                    })
                };
                /*function resetZoomCluster() {
                 $(".backMap, .nextMap").removeClass("hidden");
                 setTimeout(function(){inZoomRegion = false;},1)
                 var prov = d3.select(active.node()).attr("id"),
                 citiesCluster = d3.selectAll("circle")
                 .filter(".citiesCluster")
                 .filter(function () {
                 return d3.select(this).attr("province") == prov
                 }),
                 regionCluster = d3.selectAll("circle")
                 .filter(".regionCluster")
                 .filter(function () {
                 return d3.select(this).attr("province") == prov
                 });

                 provinces.transition()
                 .duration(750)
                 .attr("transform", "");

                 citiesCluster
                 .each(function (d) {
                 var self = d3.select(this);
                 self.attr("r", d.r)
                 .classed("hidden", true);
                 });

                 setTimeout(function(){
                 citiesCluster
                 .filter(function (d) {
                 return d.old;
                 })
                 .classed("hidden", false)

                 regionCluster
                 .classed("hidden", false);
                 })

                 }*/

                /*$(".backToMap").click(function() {
                 if (inZoomRegion) {
                 resetZoomCluster();
                 }
                 })*/
                regionClasterize();

                //Close province slider
                $(".backToMap").click(function () {
                    var path = d3.select("#" + active.datum().properties.PREABBR);
                    path.on('click').call(active.node(), active.datum())
                })

                //Slide province
                $(".backMap").click(function () {
                    var curProv = actProvinces.indexOf($(".province-active.active").attr("id")),
                        lastEl = actProvinces.length - 1;
                    var path = (curProv == 0) ?
                        d3.select("#" + actProvinces[lastEl]) :
                        d3.select("#" + actProvinces[--curProv]);
                    path.on('click').call(path.node(), path.datum());
                })
                $(".nextMap").click(function () {
                    var curProv = actProvinces.indexOf($(".province-active.active").attr("id")),
                        lastEl = actProvinces.length - 1;
                    var path = (curProv == lastEl) ?
                        d3.select("#" + actProvinces[0]) :
                        d3.select("#" + actProvinces[++curProv])
                    path.on('click').call(path.node(), path.datum());
                })

                function clicked(d) {
                    if (!(actProvinces.indexOf(d.properties.PREABBR) > -1)) return false;
                    $(".backToMap, .backMap, .nextMap").removeClass("hidden");
                    $(".map-tittle").fadeOut();
                    var prov = d.properties.PREABBR,
                        citiesClusterOld = d3.selectAll("circle")
                            .filter(".citiesCluster"),
                        regionClusterOld = d3.selectAll("circle")
                            .filter(" .regionCluster"),
                        citiesCluster = d3.selectAll("circle")
                            .filter(".citiesCluster")
                            .filter(function () {
                                return d3.select(this).attr("province") == prov
                            }),
                        regionCluster = d3.selectAll("circle")
                            .filter(".regionCluster")
                            .filter(function () {
                                return d3.select(this).attr("province") == prov
                            }),
                        provinceCluster = d3.selectAll("circle")
                            .filter(".provinceCluster"),
                        labelCircles = d3.selectAll("circle")
                            .filter(".province-label-circ"),
                        labelText = d3.selectAll("text")
                            .filter(".province-label-text");

                    if (active.node() === this) {
                        zoom = false;
                    } else {
                        zoom = true;
                        //reset old cities markers
                        citiesClusterOld.classed("hidden", true);
                        regionClusterOld.classed("hidden", true);
                    }

                    if (zoom) {
                        provinceCluster.classed("hidden", function (d) {
                            return !d3.select(this).classed("citiesCluster");
                        });
                        citiesCluster.classed("hidden", false);
                        regionCluster.classed("hidden", false);
                        labelCircles.classed("hidden", true);
                        labelText.classed("hidden", true);
                        regionClasterize();
                    } else {
                        provinceCluster.classed("hidden", false);
                        citiesCluster.classed("hidden", true);
                        regionCluster.classed("hidden", true);
                        labelCircles.classed("hidden", false);
                        labelText.classed("hidden", false);
                    }

                    //if (inZoomRegion) return false;
                    if (active.node() === this) return reset();
                    active.classed("active", false);
                    active = d3.select(this).classed("active", true);
                    var bounds = path.bounds(d),
                        dx = bounds[1][0] - bounds[0][0],
                        dy = bounds[1][1] - bounds[0][1],
                        x = (bounds[0][0] + bounds[1][0]) / 2,
                        y = (bounds[0][1] + bounds[1][1]) / 2,
                        // * 1.1 - fix for markers not cropped in bounds
                        scale = .9 / Math.max(dx / width * 1.1, dy / height * 1.1),
                        translate = [width / 2 - scale * x, height / 2 - scale * y];

                    provinces.transition()
                        .duration(750)
                        .style("stroke-width", 1.5 / scale + "px")
                        .attr("transform", "translate(" + translate + ")scale(" + scale + ")");

                    citiesCluster
                        .attr("transform", "translate( " + translate[0] + "," +
                            translate[1] +
                            " ),scale(" + scale + ")");
                    regionCluster
                        .attr("transform", "translate( " + translate[0] + "," +
                            translate[1] +
                            " ),scale(" + scale + ")");
                }

                function reset() {
                    $(".backToMap, .backMap, .nextMap").addClass("hidden");
                    $(".map-tittle").delay(250).fadeIn();
                    active.classed("active", false);
                    active = d3.select(null);
                    zoom = false;

                    provinces.transition()
                        .duration(750)
                        .style("stroke-width", "1.5px")
                        .attr("transform", "");
                }
            });
        });
    };

    function googleMap(lat, lng, zoom) {
        $(".backToMap, .backMap, .nextMap").addClass("hidden");
        $(".logo img").css("max-width", "60px");

        var map = new google.maps.Map(gmapNode, {
                zoom: parseInt(zoom),
                center: new google.maps.LatLng(lat, lng),
                disableDefaultUI: true,
                zoomControl: true,
                mapTypeControl: true,
                draggable: true,
                scrollwheel: false,
                styles: [
                    {"featureType":"water","elementType":"geometry","stylers":[{"color":"#03344f"}]},
                    {"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#054a70"}]},
                    {"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#0d8cc4"}]},
                    {"featureType":"road","elementType":"geometry","stylers":[{"color":"#076e9c"}]},
                    {"featureType":"poi","elementType":"geometry","stylers":[{"color":"#406d80"}]},
                    {"featureType":"transit","elementType":"geometry","stylers":[{"color":"#406d80"}]},
                    {"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#3e606f"},
                        {"weight":2},{"gamma":0.84}]},{"elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},
                    {"featureType":"administrative","elementType":"geometry","stylers":[{"weight":0.6},{"color":"#1a3541"}]},
                    {"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#2c5a71"}]}]
            }),
            markers = [],
            infoWindow = new google.maps.InfoWindow();

        //Set properties marker cluster
        setMarkers(all_properties);
        function setMarkers(data) {
            for (city in data) {
                for (var i = 0; i < data[city].length; i++) {
                    for (property in data[city]) {
                        var cityData = data[city][property];
                        var latLng = new google.maps.LatLng(cityData.latitude,
                            cityData.longitude);
                        var finalLatLng = latLng;
                        if (markers.length != 0) {
                            for (i = 0; i < markers.length; i++) {
                                var existingMarker = markers[i];
                                var pos = existingMarker.getPosition();
                                //if a marker already exists in the same position as this marker
                                if (latLng.equals(pos)) {
                                    //update the position of the coincident marker by applying a small multipler to its coordinates
                                    var newLat = latLng.lat();
                                    var newLng = latLng.lng() + (Math.random() - .5) / 2500;
                                    finalLatLng = new google.maps.LatLng(newLat, newLng);
                                }
                            }
                        }
                        //var map_marker = new google.maps.MarkerImage('http://fcr.fe.craftandkeen.ca/images/map_marker@1x.png');
                        var icon = new google.maps.MarkerImage('https://fcr.ca/uploads/icons/map_marker@1x.png');
                        var marker = new google.maps.Marker({
                            position: finalLatLng,
                            icon: icon,
                        });

                        var html = '<div id="infb-content">' +
                            '<div>' +
                            '<img src="' + cityData.img + '">' +
                            '<span class="infb-tittle">' + cityData.place_title + '</span>' +
                            '<div class="infb-desc">' + cityData.geo_intersetion + '<br>' +
                            //numberWithCommas(cityData.sq) + ' Sqft' +
                            '</div>' +
                            '<a href="/portfolio-leasing/view/' + cityData.code + '">' + TRANSLATIONS.more + '</a>' +
                            '</div>' +
                            '</div>';
                        bindInfoWindow(marker, map, infoWindow, html);
                        markers.push(marker);
                    }
                }
            }

            function bindInfoWindow(marker, map, infoWindow, html) {
                google.maps.event.addListener(marker, 'click', function() {
                    infoWindow.setContent(html);
                    infoWindow.open(map, marker);
                });
            }
            //close when zooming
            google.maps.event.addListener(map, 'zoom_changed', function() {
                infoWindow.close()
            });

            var markerCluster = new MarkerClusterer(map, markers, {imagePath: '/bundles/craftkeencmstheme/FCR/assets/images/portfolio-map/'});
            //fix zoom for markers, which clear one another
            google.maps.event.addListener(markerCluster, 'clusterclick', function(cluster) {
                var map = cluster.getMap(),
                    markers = cluster.getMarkers(),
                    markersNum = cluster.getSize();
                var smallDistance = Math.abs((markers[0].getPosition().lat() - markers[1].getPosition().lat()) * 1000) < 0.25;
                setTimeout(function () {
                    if (markersNum == 2 && smallDistance) map.setZoom(19);
                },100)
            });
        };

        var overlay = new google.maps.OverlayView();
        overlay.onAdd = function() {
            overlay.draw = function() {
                var projection = this.getProjection();
            }
            //Append google map layer close button
            $('#googleMap').append("<img src='/bundles/craftkeencmstheme/FCR/assets/images/portfolio-map/closed.svg' class='closeMap'>");
            if(width >= 1024) {
                $('.maphead').removeClass("hidden");
            }else {
                $("#googleMap").css("height","100%")
            }
            $('.closeMap').click(function () {
                $(".backToMap, .backMap, .nextMap").removeClass("hidden");
                $(".logo img").css("max-width", "131px");
                $('#googleMap').addClass("hidden");
                $('.maphead').addClass("hidden");
            })
        }
        overlay.setMap(map);
    }

    //Some helper funcs
    function numberWithCommas(x) {
        // Round to nearest 50
        x = Math.ceil((x+1) / 50) * 50;
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
}