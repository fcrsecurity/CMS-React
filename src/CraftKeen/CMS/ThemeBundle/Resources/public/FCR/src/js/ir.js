$(function(){


d3.select(window).on('load', resize);


d3.select(window).on('resize', resize);
	function resize() {
		$('.charts').empty();
		drawBarChart2("bar-chart-population", "barStack-ir-population.csv")
		drawBarChart2("bar-chart-household", "barStack-ir-household.csv")
		drawBarChart("bar-chart", "barStack-ir.csv");
	}


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
	}
	//Responsive sets
	var breakPoint = 768,
			breakPoint480 = 480,
			winWidth = $(window).width();

	//                        Bar Chart
	var url = '/bundles/craftkeencmstheme/FCR/assets/chart/';

	var drawBarChart = function (el, dataf) {
		var w = $('#' + el).parent().width();
		var margin = {top: 50, right: 0, bottom: 50, left: 0},
				width = w - margin.left - margin.right,
				height = 400 - margin.top - margin.bottom
		padding = 0,
				ismobile = winWidth < breakPoint;

		//Draw main sgv node
		var svg = D3Chart.mainSvg(el, margin, width, height);

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

		d3.csv(url + dataf, type, function (error, data) {
			if (error)
				throw error;

			var avg = d3.mean(data, function (d, i) {
				if (i !== data.length - 1) {
					return d.rate;
				}
			})
			avg = avg.toFixed(1);

			var maxRate = d3.max(data, function (d) {
				return d.rate;
			});

			x.domain(data.map(function (d, i) {
				if (i == data.length - 1 && ismobile) {
					return d.years.substr(2);
				} else {
					return d.years;
				}
			}));
			y.domain([0, maxRate.toFixed(0)]).nice();

			svg.selectAll(".bar")
					.data(data)
					.enter().append("rect")
					.attr("class", "bar")
					.attr("x", function (d, i) {
						if (i == data.length - 1 && ismobile) {
							return x(d.years.substr(2));
						} else {
							return x(d.years);
						}
					})
					.attr("width", x.bandwidth())
					.attr("y", function (d) {
						return height - y(d.rate);
					})
					.attr("height", 0)
					.transition()
					.duration(1000)
					.attr("y", function (d) {
						return y(d.rate);
					})
					.attr("height", function (d) {
						return height - y(d.rate);
					})

			//Draw mean line and label
			svg.data(data)
					.append("line")
					.attr("id", "line-mean")
					.attr("x1", 0)
					.attr("y1", y(avg))
					.attr("x2", 0)
					.attr("y2", y(avg))
					.transition()
					.duration(1000)
					.attr("x2", width - x.bandwidth());
			svg.append("text")
					.attr("x", function () {
						return width - x.bandwidth() - width / data.length
					})
					.attr("y", y(avg) - 10)
					//could use avg variable but had wrong number according to client specs, we can use this for future 
					.text("3.5%" + " AVG")
					.style("font-size", (ismobile ? ".5em" : ".75em"))
					.each(function () {
						padding = Math.ceil((x.bandwidth() - this.getBBox().width) / 2);
					})
					.attr("transform", (ismobile ? "translate(" + padding + ",0)" : "translate(" + padding + ",0)"))
					.style("stroke", "#288DA7")
					.style("opacity", 0)
					.transition()
					.duration(2000)
					.style("opacity", 1);

			//Draw x axis and set text
			var gx = svg.append("g")
					.attr("class", "x axis-" + el)
					.attr("transform", "translate(0," + height + ")")
					.call(xAxis);
			gx.selectAll("text").attr("dy", 40);

			//Bar Labels
			svg.selectAll(".text")
					.data(data)
					.enter()
					.append("text")
					.attr("class", "bar-label")
					.attr("x", function (d, i) {
						if (i == data.length - 1 && ismobile) {
							return x(d.years.substr(2));
						} else {
							return x(d.years);
						}
					})
					.attr("y", function (d) {
						return y(d.rate)
					})
					.attr("transform", (ismobile ? "translate(0,-5)" : "translate(20,-25)"))
					.text(function (d) {
						return d.rate + "%";
					})
					.attr("fill", "#ffffff")
					.style("font-size", (ismobile ? ".5em" : ".75em"))
					.each(function () {
						padding = Math.ceil((x.bandwidth() - this.getBBox().width) / 2);
					})
					.attr("transform", (ismobile ? "translate(" + padding + ",15)" : "translate(" + padding + ",25)"))
		});

		function type(d) {
			d.rate = +d.rate
			return d;
		}
	}

	var drawBarChart2 = function (el, dataf) {
		var w = $('#' + el).parent().width();
		var margin = {top: 120, right: 0, bottom: 75, left: 0},
				width = w - margin.left - margin.right,
				height = 450 - margin.top - margin.bottom,
				padding = 0,
				ismobile = winWidth < breakPoint;

		//Draw main sgv node
		var svg = D3Chart.mainSvg(el, margin, width, height);

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

		d3.csv(url + dataf, function (error, data) {
			if (error)
				throw error;

			var maxRate = d3.max(data, function (d) {
				return d.val;
			});
			(Number.isInteger(parseInt(maxRate))) ? maxRate : maxRate.toFixed();

			x.domain(data.map(function (d) {
				return d.labl;
			}));
			y.domain([0, maxRate]).nice();
			svg.selectAll(".bar")
					.data(data)
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
							return d.clr;
						})
					})
					.attr("width", x.bandwidth())
					.attr("y", function (d) {
						return height - y(d.val);
					})
					.attr("height", 0)
					.style("fill", function (d) {
						return d.clr
					})
					.transition()
					.duration(1000)
					.attr("y", function (d) {
						return y(d.val);
					})
					.attr("height", function (d) {
						return height - y(d.val);
					});

			//Draw x axis and set text
			var gx = svg.append("g")
					.attr("class", "x axis-" + el)
					.attr("transform", "translate(0," + height + ")")
					.call(xAxis);
			gx.selectAll("text").attr("dy", 40);

			//Bar Labels
			svg.selectAll(".text")
					.data(data)
					.enter()
					.append("text")
					.attr("class", "bar-label")
					.attr("x", function (d) {
						return x(d.labl)
					})
					.attr("y", function (d) {
						return y(d.val)
					})
					.attr("transform", (ismobile ? "translate(0,-5)" : "translate(25,-15)"))
					.text(function (d) {
						return d.sv;
					})
					.style("font-size", "1em")
					.each(function () {
						padding = Math.ceil((x.bandwidth() - this.getBBox().width) / 2);
					})
					.attr("transform", (ismobile ? "translate(" + padding + ",-15)" : "translate(" + padding + ",-25)"))
					.attr("fill", "#0079BC")
					.style("opacity", 0)
					.transition()
					.duration(1000)
					.style("opacity", 1);
		});

	}

});