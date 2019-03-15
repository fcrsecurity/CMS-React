$(document).ready(function () {
    d3.select(window).on('resize.two', resizeDividends);
    renderCharts();
    function resizeDividends() {
        $('.charts-dividends').empty();
        renderCharts();
    }

    function renderCharts() {
        var url = '/bundles/craftkeencmstheme/FCR/assets/chart/';
        var breakPoint = 768,
            breakPoint1024 = 1024,
            winWidth = $(window).width();

        var D3Chart = {
            label: function (svg, text, padding, height, dy) {
                return svg.append("text")
                    .attr("class", "left-chart-label")
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
        }

        /*              Bar Chart              */
        var drawBarChartDividends = function (el, dataf) {
            var w = $('#' + el).parent().width();
            var margin = {top: 50, right: 50, bottom: 50, left: 50},
                padding = winWidth < breakPoint ? -30 : -70;
            margin.right = winWidth < breakPoint ? 25 : 50;
            margin.left = winWidth < breakPoint ? 20 : 50;
            width = w - margin.left - margin.right,
            height = winWidth < breakPoint ? 1.5 * width : .5 * width; - margin.top - margin.bottom;

            //Draw main sgv node
            var svg = D3Chart.mainSvg(el, margin, width, height);

            var x = d3.scaleBand()
                .rangeRound([0, width])
                .padding(window.innerWidth <= 320 ? 0.1 : 0.05)
                .align(0.1);

            var y = d3.scaleLinear()
                .rangeRound([height, 0]);

            var z = d3.scaleOrdinal()
                .range(["#83A3C4", "#00A79D"]);

            //Set x-time axis and it's ticks(grid line / label)
            var xAxis = d3.axisBottom()
                .scale(x);
            //Set y-time axis and it's ticks
            var yAxis = d3.axisLeft()
                .scale(y)
                .ticks(2, "s");

            var stack = d3.stack();

            d3.csv(url + dataf, type, function (error, data) {
                if (error) throw error;
                /* Sort data if */
                // data.sort(function(a, b) { return b.total - a.total; });

                x.domain(data.map(function (d,i) {
                    return d.years;
                }));
                y.domain([0, d3.max(data, function (d) {
                    return d.total + d.total / 4;
                })]).nice();
                z.domain(data.columns.slice(2));

                svg.selectAll(".serie")
                    .data(stack.keys(data.columns.slice(2))(data))
                    .enter().append("g")
                    .attr("class", "serie")
                    .attr("fill", function (d) {
                        return z(d.key);
                    })
                    .selectAll("rect")
                    .data(function (d) {
                        return d;
                    })
                    .enter().append("rect")
                    .attr("class", function(d) {
                        return d3.select(this.parentNode).attr("fill") == "#83A3C4" ? "bar" : "bar2";
                    })
                    .attr("x", function (d) {
                        return x(d.data.years);
                    })
                    .attr("y", function (d) {
                        return y(d[1]);
                    })
                    .attr("height", function (d) {
                        return y(d[0]) - y(d[1]);
                    })
                    .attr("width", x.bandwidth())
                    .attr("transform", "translate(" + (window.innerWidth <= breakPoint ? 0 : 5) + ", 0)");

                //Draw x axis and set text
                var gx = svg.append("g")
                    .attr("class", "x axis-" + el)
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);
                if (!(window.innerWidth <= breakPoint)) {
                    gx.selectAll("text").attr("x", 5);
                    gx.selectAll("text").attr("y", 15);
                }else {
                    gx.selectAll("text").filter(function (d, i) {
                        d3.select(this).attr("transform", "rotate(65)")
                            .attr("x", 15)
                            .attr("y", 0);
                    });
                }
                gx.selectAll("g").classed("x-tick", true);
                gx.selectAll(".x-tick line").remove();

                var gy = svg.append("g")
                    .attr("class", "y axis")
                    .call(yAxis);
                if(!(window.innerWidth < breakPoint))
                {
                    gy.selectAll("text").attr("x", -20);
                }
                //Bar Labels
                var barLabl = svg.selectAll(".text")
                    .data(data)
                    .enter()
                    .append("text")
                    .attr("class", "bar-label")
                    .attr("x", function (d) {
                        return x(d.years)
                    })
                    .attr("y", function (d) {
                        return y(d.total)
                    });
                barLabl.text(function (d) {
                    return d.total.toFixed(2);
                });
                if(window.innerWidth < breakPoint) {
                    barLabl.filter(function (d) {
                        d3.select(this).attr("transform", "translate(10,-15),rotate(-90,"+x(d.years)+","+y(d.total)+")")
                    })
                } else if (window.innerWidth == breakPoint) {
                    console.log(window.innerWidth == breakPoint)
                    barLabl.filter(function (d) {
                        d3.select(this).attr("transform", "translate(15,-15),rotate(-90,"+x(d.years)+","+y(d.total)+")")
                    })
                }
                else {
                    console.log(window.innerWidth == breakPoint)
                    barLabl.each(function () {
                        padding = Math.ceil((x.bandwidth() - this.getBBox().width) / 2 + 5);
                    })
                    .attr("transform", "translate("+ padding + ",-20)")
                };

                var barLabl2 = svg.selectAll(".text")
                    .data(data)
                    .enter()
                    .filter(function(d) { return d.price2 !== 0 })
                    .append("text")
                    .attr("class", "bar-label-2")
                    .attr("x", function (d) {
                        return x(d.years)
                    })
                    .attr("y", function (d) {
                        return y(d.price + d.price2 / 2)
                    })
                barLabl2.text(function (d) {
                        return d.price2.toFixed(2);
                });
                if(window.innerWidth < breakPoint) {
                    barLabl2.filter(function (d) {
                        d3.select(this).attr("transform", "translate(10,10),rotate(-90,"+x(d.years)+","+y(d.price + d.price2 / 2)+")")
                    })
                } else if (window.innerWidth == breakPoint) {
                    barLabl2.filter(function (d) {
                        d3.select(this).attr("transform", "translate(15,10),rotate(-90,"+x(d.years)+","+y(d.price + d.price2 / 2)+")")
                    })
                }
                else {
                    barLabl2.each(function () {
                        padding = Math.ceil((x.bandwidth() - this.getBBox().width) / 2 + 5);
                    })
                        .attr("transform", "translate("+ padding + ",0)")
                };


                //Add Label
               /* if(!(window.innerWidth < breakPoint)) {
                    D3Chart.label(svg, TRANSLATIONS.dividend_graph_legend_1, padding, height, -1);
                    D3Chart.label(svg, TRANSLATIONS.dividend_graph_legend_2, padding, height, 0);
                }*/
            });

            function type(d, i, columns) {
                for (i = 1, t = 0; i < columns.length; ++i) t += d[columns[i]] = +d[columns[i]];
                d.total = t;
                return d;
            }
        }

        drawBarChartDividends("bar-chart-dividends", "barStack.csv")
    }
});	