/**
 * Property Details Demographics Charts
 * 
 */
$(document).ready(function () {
	function Utils() {}

	Utils.prototype = {
		constructor: Utils,
		isElementInView: function (element, fullyInView) {
			var pageTop = $(window).scrollTop();
			var pageBottom = pageTop + $(window).height();
			var elementTop = $(element).offset().top;
			var elementBottom = elementTop + $(element).height();

			if (fullyInView === true) {
				return ((pageTop < elementTop) && (pageBottom > elementBottom));
			} else {
				return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
			}
		}
	};

	var Utils = new Utils();
	/*Scroll listener*/
	$(window).scroll(function (e) {
		var isPolarInView = Utils.isElementInView($('svg#population-bar-chart'), false);
		if (isPolarInView) {
            if (undefined !== $('#population-bar-chart') &&
                undefined !== $('#households-bar-chart') &&
                undefined !== $('#households-income-bar-chart')) {
                Charts.drawBarChart("population-bar-chart", getChartData("population-bar-chart"));
                Charts.drawBarChart("households-bar-chart", getChartData("households-bar-chart"));
                Charts.drawBarChart("households-income-bar-chart", getChartData("households-income-bar-chart"));
            }
			$(this).off(e);
		}
	});

	function getChartData(chartId) {
		var params = $('#' + chartId).data();
		return params;
	}

	function Charts() {}
	Charts.prototype = {
		constructor: Charts,
		params: {
			breakPoint: 768,
			breakPoint480: 480,
			winWidth: $(window).width()
		},
		utils: {
			mainSvg: function (el, margin, width, height) {
				var svg;
				return svg = d3.select("svg#" + el)
						.attr("width", width + margin.left + margin.right)
						.attr("height", height + margin.top + margin.bottom)
						.append("g")
						.attr("transform", "translate(" + (margin.left + margin.right) + "," + margin.top + ")");
			},
			label: function (svg, text, padding, height, dy) {
				return svg.append("text")
						.attr("class", "left-chart-label")
						.attr("text-anchor", "middle")
						.attr("transform", "translate(" + (padding) + "," + (height / 2) + ")rotate(-90)")
						.attr("fill", "#0079BC")
						.attr("dy", dy + "em")
						.text(text);
			},
		},
		drawPolarChart: function (el, data_at) {
			var width = $('#' + el).parent().width(),
					height = width;

			var svg = d3.select("svg#" + el);
			svg.attr("width", width)
					.attr("height", height);

			var pi = Math.PI,
					radius = Math.min(width, height) / 2
			spacing = .13;

			var arcBackground = d3.arc()
					.innerRadius(function (d) {
						return d.index * radius;
					})
					.outerRadius(function (d) {
						return (d.index + spacing) * radius;
					})
					.startAngle(0 * (pi / 180))
					.endAngle(360 * (pi / 180))

			var arcBody = d3.arc()
					.innerRadius(function (d) {
						return d.index * radius;
					})
					.outerRadius(function (d) {
						return (d.index + spacing) * radius;
					})
					.startAngle(0 * (pi / 180))
					.endAngle(function (d) {
						return -(d.value * 360 * (pi / 180))
					})

			var fields = [
				{index: 0.85, value: data_at["rate1"], color: "#0861AA", legend: "1km 2,315"},
				{index: 0.7, value: data_at["rate2"], color: "#4E8CBF", legend: "3km 12,315"},
				{index: 0.55, value: data_at["rate3"], color: "#AEC3D1", legend: "5km 122,315"}
			];

			svg
					.append("g")
					.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")")
					.selectAll(".back-field")
					.data(fields)
					.enter()
					.append("path")
					.attr("class", "arc-background")
					.attr("d", function (d) {
						return arcBackground(d)
					})
					.attr("fill", "#F1F2F2");

			svg
					.append("g")
					.attr("transform", "translate(" + width / 2 + "," + height / 2 + "), rotate(-360)")
					.selectAll(".arc")
					.data(fields)
					.enter()
					.append("path")
					.attr("class", "arc")
					.attr("d", function (d) {
						return arcBody(d)
					})
					.attr("fill", function (d) {
						return d.color
					})
					.transition()    //var t = d3.transition()
					.duration(1000)
					.attrTween('d', arcTween);

			function arcTween(b) {
				var i = d3.interpolate({value: 0}, b);
				return function (t) {
					return arcBody(i(t));
				};
			}

			drawLegend()
			function drawLegend() {
				var w = $('#' + el).parent().width(),
						h = w / 3;

				var svgLeg = d3.select("svg#" + el + "-legends");
				svgLeg.attr("width", w)
						.attr("height", h);

				var legend = svgLeg.append("g")
						.attr("class", "legend")

				var wl, hl = w / 15;
				legend.selectAll('rect')
						.data(fields)
						.enter()
						.append("rect")
						.attr("x", w / 15)
						.attr("y", function (d, i) {
							return i * w / 10;
						})
						.attr("width", w / 15)
						.attr("height", w / 15)
						.style("fill", function (d) {
							return d.color;
						})

				legend.selectAll('text')
						.data(fields)
						.enter()
						.append("text")
						.text(function (d, i) {
							i += 1;
							return data_at["label" + i];
						})
						.attr("fill", "#0079BC")
						.attr("x", 3 * (w / 15))
						.attr("y", function (d, i) {
							return i * w / 10 + d3.select(this).node().getBBox().height - 5
						});

				var p = d3.select(".legend").node().getBBox().height;
				legend.attr('transform', 'translate(' + (w / 8 + p / 2) + ',' + w / 30 + ')')

			}
		},
		drawBarChart: function (el, data_at) {
			var w = $('#' + el).parent().width();
			var margin = {top: 50, right: 0, bottom: 75, left: 0},
					width = w - margin.left - margin.right,
					height = 450 - margin.top - margin.bottom,
					padding = 0;

			//Draw main sgv node
			var svg = this.utils.mainSvg(el, margin, width, height);

			// set the ranges
			var x = d3.scaleBand()
					.range([0, width])
					.padding(0.01);

			var y = d3.scaleLinear()
					.range([height, 0]);

			//Set x-time axis and it's ticks(grid line / label)
			var xAxis = d3.axisBottom()
					.scale(x)
					.ticks(0, "s")
					.tickSizeInner(0)
					.tickSizeOuter(0);

			var fields = [
				{value: data_at["rate1"], labl: data_at["label1"], dollar: data_at["dollar"], color: "#0861AA"},
				{value: data_at["rate2"], labl: data_at["label2"], dollar: data_at["dollar"], color: "#4E8CBF"},
				{value: data_at["rate3"], labl: data_at["label3"], dollar: data_at["dollar"], color: "#AEC3D1"}
			];

			var maxRate = d3.max(fields, function (d) {
				return d.value;
			});
			x.domain(fields.map(function (d) {
				return d.labl;
			}));
			y.domain([0, maxRate.toFixed(0)]).nice();
			svg.selectAll(".bar")
					.data(fields)
					.enter().append("rect")
					.attr("class", "bar")
					.attr("x", function (d) {
						return x(d.labl);
					})
					.on('mouseover', function (d) {
						d3.select(this).style("fill", function (d) {
							return '#0079BC ';
						})
					})
					.on('mouseout', function (d) {
						d3.select(this).style("fill", function (d) {
							return d.color;
						})
					})
					.attr("width", x.bandwidth())
					.attr("y", function (d) {
						return height - y(d.value);
					})
					.attr("height", 0)
					.style("fill", function (d) {
						return d.color
					})
					.transition()
					.duration(1000)
					.attr("y", function (d) {
						return y(d.value);
					})
					.attr("height", function (d) {
						return height - y(d.value);
					})

			//Draw x axis and set text
			var gx = svg.append("g")
					.attr("class", "x axis-" + el)
					.attr("transform", "translate(0," + height + ")")
					.call(xAxis);
			gx.selectAll("text").attr("dy", 40)
					.style("opacity", 0)
					.transition()
					.duration(1000)
					.style("opacity", 1);

			//Bar Labels
			svg.selectAll(".text")
					.data(fields)
					.enter()
					.append("text")
					.attr("class", "bar-label")
					.attr("x", function (d) {
						return x(d.labl)
					})
					.attr("y", function (d) {
						return y(d.value)
					})
					.text(function (d) {
						var v = d.value.toFixed().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
						return ((d.dollar) ? "$" : "") + v;
					})
					.style("font-size", "1em")
					.each(function () {
						padding = Math.ceil((x.bandwidth() - this.getBBox().width) / 2);
					})
					.attr("transform", (Charts.params.winWidth < Charts.params.breakPoint ? "translate(" + padding + ",-5)" : "translate(" + padding + ",-15)"))
					.style("opacity", 0)
					.transition()
					.duration(1000)
					.style("opacity", 1);
		}
	}

	var Charts = new Charts();
	d3.select(window).on('resize', resize);
	function resize() {
		$('.charts').empty();

		if (undefined !== $('#population-bar-chart') &&
			undefined !== $('#households-bar-chart') &&
			undefined !== $('#households-income-bar-chart')) {
            Charts.drawBarChart("population-bar-chart", getChartData("population-bar-chart"));
            Charts.drawBarChart("households-bar-chart", getChartData("households-bar-chart"));
            Charts.drawBarChart("households-income-bar-chart", getChartData("households-income-bar-chart"));
        }
	}

});