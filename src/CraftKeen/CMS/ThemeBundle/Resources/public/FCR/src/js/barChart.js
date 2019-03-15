'use strict';
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
    d3.select(window).on('load', draw);
    d3.select(window).on('resize', draw);
    $(window).on('scroll', function(e) {
        var isChartInView = Utils.isElementInView($('svg.charts')[0], false);
        if (isChartInView) {
            draw();
            $(this).off(e)
        }
    });
    $(window).on('scroll', function(e) {
        var isChartInView = Utils.isElementInView($('svg.charts')[2], false);
        if (isChartInView) {
            draw();
            $(this).off(e)
        }
    });
});

function draw() {
    $('.charts').each(function () {
        var el = $(this).attr('id')
        var type = $('#'+el).attr("chart-type"),
            chartData = $('#' + el).attr("chart-data"),
            chartID = parseInt($('#' + el).attr("data-id")),
            label = $('#chart-label-' + chartID).text();
        //Clear svg
        $('.charts-'+chartID).empty();
        //Create widget instance for each chart
        if ( undefined !==  chartData) {
            var data = JSON.parse(chartData),
                copyData = JSON.parse(chartData);
            var chart = new ChartMacros(el, type, chartID, data, copyData, label);
        }
    })
}
/**
 * ChartMacros class.
 *
 * @constructor
 * @param {String} _svg - The svg container.
 * @param {String} _id - The id macros(widget).
 * @param {Array} _data  - The macros data object.
 * @param {String} _label  - The chart label.
 */
function ChartMacros(svg, type, id, data, copyData, label) {
    this._svg = svg;
    this._type = type;
    this._id = id;
    this._data = data;
    this._label = label;
    //Copy of data for comparison: widget was changed or not
    this._copydata = copyData;
    //Init Chart & Table
    this.d3Chart = new Charts();
    this.chartDraw(this._svg);
    this.table = this.initTable();
    this.initListeners();
}
/**
 * Init ModalTable instance.
 */
ChartMacros.prototype.initTable = function () {
    var tableModal = 'TableModal-' + this._id,
        table;
    if(this._data.length > 0) {
        table = new ModalTable(this._id, this._svg, tableModal, this._data);
    }
    return table;
}
/**
 * Draw Chart.
 *
 * @param {String} el - The svg selector.
 */
ChartMacros.prototype.chartDraw = function (el) {
    if(this._data.length > 0) {
        this.d3Chart[this._type](el, this._data)
    }
}
/**
 * Redraw chart.
 */
ChartMacros.prototype.chartRedraw = function () {
    this.chartDraw(this._svg);
}
/**
 * Add and update Chart Widget in widget list.
 *
 * @param {Number} chartID - The chart ID .
 * @param {Array} chartData - The array of items objects.
 * @param {String} chartLabel - The chart label .
 */
ChartMacros.prototype.setChartMacrosData = function (chartID, chartData, chartLabel, chartType, chartPropNames, chartFootnes, chartTools, chartToolsData, chartClass) {
    var widgets = window.widgets;
    //if widget is exist - delete it
    widgets.map(function (obj, i) {
        if (chartID in obj) {
            widgets.splice(i, 1)
        }
    })
    //And write with new props
    var w = {};
    w[chartID]= {
        'config': {'empty': null},
        'data': {
            'items' : chartData,
            'label' : chartLabel,
            'type' : chartType,
            'propNames' : chartPropNames,
            'tableFootnes' : chartFootnes,
            'tools' : typeof chartTools !== "undefined" ? JSON.parse(chartTools) : '',
            'toolsData' : typeof chartTools !== "undefined" ? JSON.parse(chartToolsData) : '',
            'class' : chartClass
        }
    }
    widgets.push(w);
}
/**
 * Bind event listeners.
 */
ChartMacros.prototype.initListeners = function () {
    var modalContainer = $('#TableModal-' + this._id + ' .modal-body');
    var drawChart = this.chartRedraw.bind(this),
        setMacrosData = this.setChartMacrosData.bind(this),
        chartData = this._data,
        chartLabel = this._label,
        chartType = modalContainer.attr('data-chart-type'),
        chartPropNames = modalContainer.attr('data-table'),
        chartFootnes = modalContainer.attr('data-table-footnotes'),
        chartTools = modalContainer.attr('data-tools'),
        chartToolsData = modalContainer.attr('data-tools-data'),
        chartClass = modalContainer.attr('data-chart-class'),
        copyChartData = this._copydata,
        chartID = this._id;
    //Save changes
    $('#accept-changes-' + this._id).on('click', function () {
        //If something was changed, than add widget to list of changed widgets
        if (JSON.stringify(copyChartData) !== JSON.stringify(chartData)) {
            setMacrosData(chartID, chartData, chartLabel, chartType, chartPropNames, chartFootnes, chartTools, chartToolsData, chartClass);
            $('.charts-' + chartID).empty();
            drawChart();
        }
        //Redraw chart anyway
        $('.charts-' + chartID).empty();
        drawChart();
        $('.close').trigger( "click" );
    })
    //Save update label value
    $('#chart-label-' + this._id).on('keyup', function () {
        chartLabel = $(this).text();
        setMacrosData(chartID, chartData, chartLabel, chartType, chartPropNames, chartFootnes , chartTools, chartToolsData, chartClass);
    })
}
/**
 * ModalTable class.
 *
 * @constructor
 * @param {String} _chartID - The widget id .
 * @param {String} _id - The id of relative macros DOM node.
 * @param {String} _container - The container in which placed table.
 * @param {Array} _data - The data for table.
 * @param {Number} _propsCount - The count of data properties.
 */
function ModalTable(chartID, id, container, data) {
    // window.console.log(this);
    this._chartID = chartID;
    this._id = id;
    this._container = container;
    this._data = data;
    this._propsCount = Object.keys(this._data[0]);
    //Init table
    this.drawTable();
}
/**
 * Bind event listeners.
 */
ModalTable.prototype.initListeners = function () {
    var removeRow = this.removeRow.bind(this),
        addRow = this.addRow.bind(this),
        updateRow = this.updateRow.bind(this);
    $('.remove-row-' + this._id).each(function (i, item) {
        $(item).on('click', function () {
            removeRow(i)
        })
    })
    $('#add-row-' + this._id).on('click', function () {
        addRow()
    })
    //Inline editing
    $("#" + this._container + " td").on('keyup', function () {
        updateRow($(this))
    })
    var chartID = this._chartID,
        chartData = this._data,
        updateCustomProp = this.updateCustomPropInRow.bind(this);
    //ColorList change listener
    $('#'+chartID+'.color-tool-li').on('click', function () {
        var li = $(this),
            td = li.closest("td"),
            color = li.attr('data-color');
        var btn = li.closest(".dropdown").children("button"),
            curBtnClr = li.closest(".dropdown").children("button").attr("class").split(" ");
        btn.removeClass(curBtnClr[curBtnClr.length-1])
            .addClass(color);
        updateCustomProp(td, color)
    });
    //Limiter of average value
    $('[name="avg-value"]').on('click', function () {
        var td = $(this).closest("td");
        $('[name="avg-value"]').each(function() {
            $(this).removeAttr('checked')
        });
        $(this).prop('checked', true)
        $(this).attr('checked', 'checked')
        //clear all values
        $.each(chartData, function(i) {
            chartData[i].avg = 'n';
        });
        //Set new
        updateCustomProp(td, 'y')
    })
}
/**
 * Draw Head section for table.
 */
ModalTable.prototype.drawHead = function () {
    var head = '',
        chartProps = $('#' + this._container + ' .modal-body').attr('data-table').split(",");

    for (var i = 0; i < chartProps.length; i++) {
        var th = '<th>' + chartProps[i] + '</th>';
        head += th;
    }
    head += '<th>Actions</th>';
    return '<thead>' +
        '<tr>' +
        head +
        '</tr>' +
        '</thead>'
};
/**
 * Draw Raw sections for Body table section.
 *
 * @param {Number} id - The Row ID.
 * @param {Object} tools - The tool plugins.
 * @param {Object} toolsData - The data for tool plugins.
 */
ModalTable.prototype.drawRow = function (id, tools, toolsData) {
    var tr = '',
        trHTML;
    //Create Tools instance if tools exist
    if(tools && toolsData) {
        var Tool = new Tools();
    }
    //build tr, reset tr after each loop
    tr = '';
    var editable = true;
    //concat all tr in td
    for (var prop in this._data[id]) {
        //if current prop exist in tools props
        if(tools && toolsData && Object.keys(tools).indexOf(prop) !== -1 ) {
            editable = false
            /* Call plugin function with data */
            var toolName = tools[prop];
            /* id: chart ID;
             this._data[id][prop]: current value;
             toolsData[toolName]: custom plugin data */
            trHTML = Tool[toolName](this._chartID, this._data[id][prop], toolsData[toolName]);
        } else {
            trHTML = this._data[id][prop]
        }
        var td = '<td name="' + prop + '" data-index="' + id + '" contenteditable="'+editable+'">'
            + trHTML
            + '</td>';
        tr += td;
    }
    tr += '<td><button type="button" class="btn btn-default remove-row remove-row-' + this._id + '">'+
        '<span class="glyphicon glyphicon-trash"></span>'+
        '</button></td>'
    return '<tr id="' + id + '">' + tr + '</tr>';
}
/**
 * Draw Body sections for table.
 */
ModalTable.prototype.drawBody = function () {
    var body = '',
        //tools - list of custom plugins; toolsData - its data
        tools = $('#' + this._container + ' .modal-body').attr('data-tools'),
        toolsData = $('#' + this._container + ' .modal-body').attr('data-tools-data');
    if (tools !== undefined && tools !== '' && toolsData !== undefined && toolsData !== '' ) {
        tools = JSON.parse(tools),
        toolsData = JSON.parse(toolsData);
    } else {
        console.error("!!! data-tools & datatools-data attributes don't defined in macro: " + this._id)
    }
    for (var i = 0; i < this._data.length; i++) {
        var tr = this.drawRow(i, tools, toolsData)
        body += tr;
    }
    return '<tbody>' +
        body +
        '</tbody>';
}
/**
 * Draw Table by all sections.
 */
ModalTable.prototype.drawTable = function () {
    // console.log('tableData: ', this._data)
    // Add footnotes if it exist
    var ft = '',
        footnotes = $('#' + this._container + ' .modal-body').attr('data-table-footnotes').split(",");
    for (var i = 0; i < footnotes.length; i++) {
        var p = '<p class="chart-table-footnotes">' + footnotes[i] + '</p>';
        ft += p;
    }
    var table = '<table class="table table-hover" id="table-' + this._id + '">' +
        this.drawHead() +
        this.drawBody() +
        '</table>' +
        '<div>' + ft + '</div>' +
        // window.console.log(this._data)
        '<a class="btn btn-info" id="add-row-' + this._id + '"><span class="glyphicon glyphicon-plus"></span></a> ';
    $('#' + this._container + ' .modal-body').html(table);
    this.initListeners();
}
/**
 * Remove Raw from table and data.
 *
 * @param {Number} id - The Row ID .
 */
ModalTable.prototype.removeRow = function (id) {
    this._data.splice(id, 1);
    this.drawTable();
}
/**
 * Add Raw to table and data.
 */
ModalTable.prototype.addRow = function () {
    var item = {};
    var props = this._propsCount;
    props.forEach(function (p) {
        item[p] = '';
    })
    this._data.push(item);
    this.drawTable();
}
/**
 * Update Raw to table and data.
 *
 * @param {object} td - The Row td selector.
 */
ModalTable.prototype.updateRow = function (td) {
    var prop = td.attr("name"),
        idx = td.attr("data-index");
    this._data[idx][prop] = td.text();
}
/**
 * Update custom prop in Raw.
 *
 * @param {object} td - The Row td selector.
 */
ModalTable.prototype.updateCustomPropInRow = function (td, val) {
    var prop = td.attr("name"),
        idx = td.attr("data-index");
    this._data[idx][prop] = val;
}

//List of tools
var Tools = function() {
    this.ColorList = function(id, curColor, colors) {
        var colorItems = '',
            dd;
        //from string to array
        colors = colors.split(',');
        //
        colors.forEach(function (color) {
            var li = '<li id="'+id+'" class="color-tool-li '+color+'" data-color="'+color+'"></li>';
            colorItems += li;
        });
        dd = '<div class="dropdown">'+
                '<button class="btn dropdown-toggle color-tool-button '+curColor+'" type="button" data-toggle="dropdown"></button>'+
                '<ul class="dropdown-menu color-tool-ul">'+
                    colorItems +
                '</ul>'+
            '</div>'
        return dd;
    },
        this.AvgBorder = function(id, curAvg) {
            var checked = curAvg == 'y' ? 'checked="checked"' : '',
                checkbox = '<input type="checkbox" name="avg-value" ' + checked + '>';
            return checkbox;
        }
}

//Chart settings
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

var breakPoint = 768,
    breakPoint480 = 480,
    winWidth = $(window).width();
//Charts
var Charts = function() {
    this.BarChart = function (el, data) {
        var w = $('#' + el).parent().width();
        var margin = {top: 60, right: 0, bottom: 75, left: 0},
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

        var maxRate = d3.max(data, function (d) {
            return parseInt(d.val);
        });

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
                d3.select(this).attr("class", "bar-hover")
            })
            .on('mouseout', function (d) {
                d3.select(this).attr("class", function (d) {
                    return d.clr;
                })
            })
            .attr("width", x.bandwidth())
            .attr("y", function (d) {
                return height - y(d.val);
            })
            .attr("height", 0)
            .attr("class", function (d) {
                return d.clr;
            })
            //animation
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
            .attr("class", "bar-label")
            .style("opacity", 0)
            .transition()
            .duration(2500)
            .style("opacity", 1);
    },
    this.BarChartAvg = function (el, data) {
        var w = $('#' + el).parent().width();
        var margin = {top: 50, right: 0, bottom: 75, left: 0},
            width = w - margin.left - margin.right,
            height = 400 - margin.top - margin.bottom,
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
        //AVG value
        var avgIndex;
        data.forEach(function (d, i) {
            if (data[i].avg == 'y') {
                avgIndex = i +1;
            }
        })
        var avgData = data.slice(0, avgIndex),
            notInAvg = data.length - avgIndex;
        var avg = d3.mean(avgData, function (d) {
            return d.rate;
        })
        avg = avg.toFixed(1);

        var maxRate = d3.max(data, function (d) {
            return Number(d.rate);
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
            //animation
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
            .attr("y2", y(avg))
            .transition()
            .duration(1000)
            .attr("x2", width - x.bandwidth() * notInAvg);
        svg.append("text")
            .attr("x", function () {
                return width - x.bandwidth() * (notInAvg + 1)
            })
            .attr("y", y(avg) - 10)
            //could use avg variable but had wrong number according to client specs, we can use this for future
            .text(avg + "% AVG")
            .style("font-size", (ismobile ? ".5em" : ".75em"))
            .each(function () {
                padding = Math.ceil((x.bandwidth() - this.getBBox().width) / 2);
            })
            .attr("transform", (ismobile ? "translate(" + padding + ",0)" : "translate(" + padding + ",0)"))
            .style("stroke", "#288DA7")
            .style("opacity", 0)
            .transition()
            .duration(2500)
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
    }
}