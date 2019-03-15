$(document).ready(function () {
    var QCTRANSLATIONS = window.portfolioPageTranslations;

    var lang = $('#system-locale').val();
    //Change lang to charts dates and datapicker on french
    if ( lang == 'fr_CA' ) {
        d3.timeFormatDefaultLocale({
            "decimal": ",",
            "thousands": ".",
            "grouping": [3],
            "currency": ["€", ""],
            "dateTime": "%a %b %e %X %Y",
            "date": "%d-%m-%Y",
            "time": "%H:%M:%S",
            "periods": ["AM", "PM"],
            "days": ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
            "shortDays": ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
            "months": ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
            "shortMonths": ["Janv", "Févr", "Mars", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"]
        })
    }
    //-------------------stock price------------------
    $.stokePriceWidget(false);
    //-------------------END stock price------------------

    $('#dataTable-qc').hide();
    (function yahooHistoricalPrices() {
        var dataTable = $('#dataTable-qc'),
            timeFrame = '1W',
            dividends = '&filter[dividentsOnly]=true',
            dateFromInput = $('.historical  .datetimepickerFrom'),
            dateToInput = $('.historical .datetimepickerTo'),
            getPriceTrigger = false;

        this.loadData = function (clicked) {
            console.log('T1');
            $('#dataTable-qc').show();
            var api_get_historical_data = '/investors/historical-data/daily?offset=0&limit=0&filter[timeFrame]=';

            var dateFrom, dateTo;
            if( $('.historical .datetimepickerFrom').val() == 'dd/mm/yyyy' || $('.historical .datetimepickerTo').val() == 'dd/mm/yyyy' ) {
                dateFrom = moment().format("YYYY-MM-DD"),
                dateTo = moment().format("YYYY-MM-DD");
            } else {
                dateFrom = (dateFromInput.val() != '' ? moment(dateFromInput.val(), 'DD/MM/YYYY').format("YYYY-MM-DD") : moment().subtract(1, 'years')),
                dateTo = (dateToInput.val() != '' ? moment(dateToInput.val(), 'DD/MM/YYYY').format("YYYY-MM-DD") : moment());
            }

            if(!clicked) {
                api_get_historical_data = api_get_historical_data + timeFrame;
            } else {
                api_get_historical_data = api_get_historical_data + '&filter[fromDate]=' + dateFrom + '&filter[toDate]=' + dateTo;
            }
            d3.json(api_get_historical_data, function(d) {
				if ( null !== d.data ) {
					this.displayData(d.data);
				}
            });
            getPriceTrigger = false;
        };

        this.displayData = function (data) {
            console.log('T2');
            var body = '', head = '',
                tbody = dataTable.find('tbody'),
                thead = dataTable.find('thead');

            var headerNames = d3.keys(data[0]);

            if (head == '') {
                head += '<tr>';
                headerNames.forEach(function(d) {
                    if ( d !== 'adjClose' && d !== 'id' ) {
                        head += '<th>' + toTitleCase(d) + '</th>';
                    }
                })
                head += '</tr>';
            }

            data.forEach(function (d) {
                body += '<tr>';

                d3.keys(d).map(function (k) {
                    if (k !== 'adjClose' && k !== 'id') {
                        var v = d[k];
                        if (k !== 'date' && k !== 'volume') {
                            v = parseFloat(Number(v)).toFixed(2);
                        }
                        if (k == 'date') {
                            v = moment(v.date, 'YYYY-MM-DD').format("YYYY/MM/DD");
                        }
                        if (k == 'volume' && v !== null) {
                            v = v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                        body += '<td>' + v + '</td>';
                    }
                })
                body += '</tr>';
            })

            if ($.fn.dataTable.isDataTable(dataTable)) {
                dataTable.DataTable().destroy();
            }

            thead.empty();
            tbody.empty();

            thead.append(head);
            tbody.append(body);
			window.console.log(lang);
			var translationLang = 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/English.json';
			if ( 'fr_CA' == lang ) {
				var translationLang = 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json';
			}

            dataTable.DataTable({
                searching: false,
                paging: true,
                info: false,
                processing: true,
                order: [],
				language: {
					url: translationLang
				}
            });

			console.log('OFF LOADER');
        };

        this.onTimeFrameClick = function (item) {
            console.log('T3');
            $('.time-link a').removeClass("active");
            $(item.target).addClass("active");

            switch (item.target.text) {
                case 'Daily':
                    timeFrame = '1D';
                    break;
                case 'Weekly':
                    timeFrame = '1W';
                    break;
                case 'Monthly':
                    timeFrame = '1M';
                    break;
                case 'Dividends Only':
                    timeFrame = 'AllYears' + dividends;
                    break;
                default:
                    timeFrame = '1D';
            }

            this.loadData(getPriceTrigger);

            return false;
        };


        this.onGetPriceClicked = function () {
            console.log('T4');
            getPriceTrigger = true;
            this.loadData(getPriceTrigger);
            return false;
        };

        $('.historical .time-link').find('li a').click($.proxy(this.onTimeFrameClick, this));
        $('.historical .getPrice').click($.proxy(this.onGetPriceClicked, this));
    }());

    (function renderCharts() {

        var data = [],
            date,
            range = '1Y',
            urlParams = '&filter[timeFrame]=1Y',
            dateFromInput = $('.quotes-charts  .datetimepickerFrom'),
            dateToInput = $('.quotes-charts .datetimepickerTo'),
            dateFrom, dateTo;


        this.render = function() {
            console.log('T5');
            //Responsive sets
            var breakPoint = 768,
                breakPoint480 = 480,
                winWidth = $(window).width();

            if (undefined === data[0]) {
                console.log('T10. No Data Found!');
                $('.loading').hide();
                return;
            }

            var average,
                lastEl = data.length - 1,
                firstv = data[0].value,
                lastv = data[lastEl].value;
                average = (lastv - firstv) / firstv * 100;
            $(".average-percents").html("Avg: " + average.toFixed(2) + "%")

            var D3Chart = {
                label: function (svg, text, padding, height, dy) {
                    return svg.append("text")
                        .attr("text-anchor", "middle")
                        .attr("transform", "translate(" + (padding) + "," + (height / 2) + ")rotate(-90)")
                        .attr("fill", "#0079BC")
                        .attr("dy", dy + "em")
                        .text(text);
                },
                mainSvg: function (el, margin, width, height) {
                    var svg;
                    return svg = d3.select("svg#" + el)
                        .attr("width", width + margin.left + margin.right)
                        .attr("height", height + margin.top + margin.bottom)
                        .append("g")
                        .attr("transform", "translate(" + (margin.left + margin.right) + "," + margin.top + ")");
                }
            };

            var drawAreaChart = function (el) {
                var parseDate = d3.timeParse("%Y-%m-%d-%H-%M");
                var timeFormat = d3.timeFormat("%I-%M");

                draw(el, data)

                function draw(el, data) {
                    //Set width for current svg chart
                    var w = $('#' + el).parent().width();
                    //Set margins
                    var margin = {top: 50, right: 50, bottom: 50, left: 50},
                        padding = winWidth < breakPoint ? -35 : -75;
                    margin.right = winWidth < breakPoint ? 25 : 50;
                    margin.left = winWidth < breakPoint ? 20 : 50;
                    var width = w - margin.left - margin.right,
                        height = (winWidth < breakPoint ? .7 : .45) * width - margin.top - margin.bottom;
                    // Set min/max
                    var min = d3.min(data, function(d) { return d.value; }),
                        max = d3.max(data, function(d) { return d.value; })
                    //Draw main sgv node
                    var svg = D3Chart.mainSvg(el, margin, width, height);
                    //Set domain, range for x-time axis
                    var x = d3.scaleTime()
                        .domain(d3.extent(data, function (d) {
                            return d.date;
                        }))
                        .range([0, width]);
                    //Set domain, range for y-value axis
                    var y = d3.scaleLinear()
                        .domain([ min, max + 0.005 * max ])
                        .range([height, 0]);
                    //Set x-time axis and it's ticks(grid line / label)
                    var xAxis = d3.axisBottom()
                        .scale(x)
                        .tickSizeInner(-height)
                        .tickSizeOuter(0);
                    //Set y-time axis and it's ticks
                    var yAxis = d3.axisLeft()
                        .scale(y)
                        .tickSizeInner(-(width + 10))
                        .tickSizeOuter(0)
                    if (window.innerWidth < breakPoint) {
                        yAxis.ticks(6)
                    }
                    else {
                        yAxis.ticks(10)
                    }
                    //Set area
                    var area = d3.area()
                        .x(function (d) {
                            return x(d.date);
                        })
                        .y0(height)
                        .y1(function (d) {
                            return y(d.value);
                        });
                    //Set tip
                    var tip = svg.append("text")
                        .attr("class", "series-info")
                        .attr("x", width < breakPoint ? -30 : 0)
                        .attr("y", - margin.top/2)
                        .attr("dy", ".35em")
                        .attr("fill", "#0079BC");

                    //Draw x axis and set text
                    var gx = svg.append("g")
                        .attr("class", "x axis-" + el)
                        .attr("transform", "translate(0," + height + ")")
                        .call(xAxis);
                    gx.selectAll("g").classed("x-tick", true);
                    gx.selectAll("text").attr("dy", 20);
                    //Draw y axis and set text
                    if (window.innerWidth <= breakPoint) {
                        gx.selectAll("text").filter(function (d, i) {
                            d3.select(this).attr("transform", "rotate(65)")
                                .attr("x", 15)
                                .attr("y", 0);
                        });
                    }
                    //Draw y axis and set text
                    var gy = svg.append("g")
                        .attr("class", "y axis")
                        .call(yAxis);
                    gy.selectAll("g").classed("y-tick", true);
                    if (!(window.innerWidth < breakPoint)) {
                        gy.selectAll("text").attr("x", -20);
                    }
                    //Draw area
                    svg.append("path")
                        .datum(data)
                        .attr("class", "area")
                        .attr("d", area);
                    //Draw dots for tip
                    svg.selectAll("dot")
                        .data(data)
                        .enter().append("circle")
                        .classed("dot", true)
                        .attr("r", 7)
                        .attr("cx", function (d) {
                            return x(d.date);
                        })
                        .attr("cy", function (d) {
                            return y(d.value);
                        })
                        .on("mouseover", function (d) {
							// window.console.log(d);
                            tip.transition()
                                .duration(200)
                                .style("opacity", .9);
                            //Full tip by data
//                            tip.html(d.date + "<br/>" + d.value.toFixed(2))
							tip.html(generateInfoBox(d))
                                .style("left", (d3.event.pageX - 15) + "px")
                                .style("top", (d3.event.pageY - 50) + "px");
                        })
                        .on("mouseout", function (d) {
//                            tip.transition()
//                                .duration(500)
//                                .style("opacity", 0);
                        });
                    //Add Label
                    D3Chart.label(svg, QCTRANSLATIONS.stoke_price, padding, height, 0);
                }
            };

            var drawBarChart = function (el) {

                draw(el, data)
                function draw(el, data) {

                    var w = $('#' + el).parent().width();
                    var margin = {top: 50, right: 50, bottom: 50, left: 50},
                        padding = winWidth < breakPoint ? -35 : -75;
                    margin.right = winWidth < breakPoint ? 25 : 50;
                    margin.left = winWidth < breakPoint ? 20 : 50;
                    var width = w - margin.left - margin.right,
                        height = 150 - margin.top - margin.bottom;

                    // Set min/max
                    var min = d3.min(data, function (d) {return d.volume;}),
                        max = d3.max(data, function (d) {return d.volume;})
                    //Draw main sgv node
                    var svg = D3Chart.mainSvg(el, margin, width, height);

                    // set the ranges
                    var x = d3.scaleBand()
                        .range([0, width])
                        .padding(0.05);

                    var y = d3.scaleLinear()
                        .range([height, 0]);

                    //Set x-time axis and it's ticks(grid line / label)
                    var xAxis = d3.axisBottom()
                        .scale(x)
                        .ticks(5, "s")
                        .tickSizeInner(-height)
                        .tickSizeOuter(0);

                    //Set y-time axis and it's ticks
                    var yAxis = d3.axisLeft()
                        .scale(y)
                        .ticks(3, "s")
                        .tickSizeInner(-(width * 1.1))
                        .tickSizeOuter(0);

                    x.domain(data.map(function (d) {
                        return d.date;
                    }));
                    y.domain([min, max]).nice();

                    //Set tip
                    var tip = svg.append("text")
                        .attr("class", "volume-info")
                        .attr("x", 0)
                        .attr("y", -margin.top / 2)
                        .attr("dy", ".35em")
                        .attr("fill", "#0079BC");
                    //Draw x axis and set text
                    var gx = svg.append("g")
                        .attr("class", "x axis-" + el)
                        .attr("transform", "translate(0," + height + ")")
                        .call(xAxis);
                    gx.selectAll("text").classed("hidden", "true");

                    var gy = svg.append("g")
                        .attr("class", "y axis")
                        .call(yAxis)
                    if (!(window.innerWidth < breakPoint)) {
                        gy.selectAll("text").attr("x", -20);
                    }

                    svg.selectAll(".bar")
                        .data(data)
                        .enter().append("rect")
                        .attr("class", "bar")
                        .attr("x", function (d) {
                            return x(d.date);
                        })
                        .attr("width", x.bandwidth())
                        .attr("y", function (d) {
                            return y(d.volume);
                        })
                        .attr("height", function (d) {
                            return height - y(d.volume);
                        });
                    //Add Label
                    D3Chart.label(svg, QCTRANSLATIONS.volume, padding, height, 0);
                };
            }

            drawAreaChart("area-chart");
            drawBarChart("volume-bar-chart");

            $('.loading').hide();
        };

        this.onWResize = function () {
            $('.charts').empty();
            this.render();
        };

        this.loadRangeData = function (clicked) {
            console.log('T6');
            var rangeC = false;
            dateFrom = dateTo = '';
            urlParams = "&filter[timeFrame]=" + range;
            if (clicked) {
                rangeC = true;
            }
            this.loadChartData(rangeC);
        };

        this.loadPeriodData = function () {
            console.log('T7');
            var dateFromVal = moment(dateFromInput.val(),'DD/MM/YYYY') ,
                dateToVal = moment(dateToInput.val(),'DD/MM/YYYY');
            dateFrom = (dateFromInput.val() != '' ? dateFromVal.format("YYYY-MM-DD") : moment().subtract(1, 'years'));
            dateTo = (dateToInput.val() != '' ?  dateToVal.format("YYYY-MM-DD") : moment());

            urlParams = "&filter[fromDate]=" + dateFrom + "&filter[toDate]=" + dateTo;
            this.loadChartData();
            return false;
        };

        this.loadChartData = function (rClick) {
            console.log('T8');
            $('.loading').show();

            var api_get_historical_data = '/investors/historical-data/daily?offset=0&limit=0' + urlParams;
            d3.json(api_get_historical_data, function(d) {
                data = [];
                    d.data.forEach(function (d) {
                        date = moment(d.date.date, "YYYYMMDD");
                        data.push({
                            date: date.toDate(),
                            value: Number(d.close),
                            volume: Number(d.volume)
                        });
                    })
                //Sets date values to From - To inputs
                if (dateFrom == '' && dateTo == '' && rClick) {
                    var from, to,
                        d_from = d3.min(data, function(d) { return d.date; }),
                        d_to = d3.max(data, function(d) { return d.date; });
                    from = moment(d_from, "YYYYMMDD").format("DD/MM/YYYY");
                    to = moment(d_to, "YYYYMMDD").format("DD/MM/YYYY");
                    //dateFromInput.val(from);
                    //dateToInput.val(to);
                }
                $('.charts').empty();
                this.render();
            });
        };

        //set time Rang
        this.onTimeClick = function (item) {
            console.log('T9');
            $(".time-link a").removeClass("active");
            $(item.target).addClass("active");
            range = item.target.text;
            var clicked = true;
            this.loadRangeData(clicked);
            return false;
        };

        d3.select(window).on('resize', $.proxy(this.onWResize, this));
        $('.quotes-charts .time-link').find('li a').click($.proxy(this.onTimeClick, this));
        $('.quotes-charts .getPrice').click($.proxy(this.loadPeriodData, this));
        this.loadRangeData();
        this.loadChartData();
    }());

    $('.datetimepickerFrom').val(moment().subtract(1, 'y').format('DD/MM/YYYY')).datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        endDate: '0d',
        language: (lang == 'fr_CA') ? 'fr' : 'en'
    });

    $('.datetimepickerTo').val(moment().format('DD/MM/YYYY')).datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        endDate: '0d',
        language: (lang == 'fr_CA') ? 'fr' : 'en'
    });

    $('.historical .datetimepickerFrom').val('dd/mm/yyyy');
    $('.historical .datetimepickerTo').val('dd/mm/yyyy');
});

function toTitleCase(s)
{	var str = s;
	if ( undefined !== window.portfolioPageTranslations[s] ) {
		str = window.portfolioPageTranslations[s];
	}
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

function generateInfoBox(data) {
	return data.value.toFixed(2);
}