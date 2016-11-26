<?php

include ("lake-app.inc");
include(APP_WEB_DIR.'/inc/header.inc');

use \com\indigloo\Url ;

$gparams = new \stdClass ;
$gparams->debug = false ;
$gparams->base = Url::base() ;

if(array_key_exists("jsdebug", $_REQUEST)) {
    $gparams->debug = true ;
}

?>
<!DOCTYPE html>
<meta charset="utf-8">
<style>

    .axis text {
        font: 10px sans-serif;
    }

    .axis path,
    .axis line {
        fill: none;
        stroke: #000;
        shape-rendering: crispEdges;
    }

    .axis path {
        display: none;
    }

    .isoline {
        fill: none;
        stroke: #fff;
    }

</style>
<body>
<script src="/assets/d3.v3.js"></script>
<script src="/assets/contour.js"></script>
<script>

    var margin = {top: 20, right: 20, bottom: 30, left: 30},
        width = 960 - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;

    var x = d3.scale.linear()
        .range([0, width]);

    var y = d3.scale.linear()
        .range([height, 0]);

    var color = d3.scale.linear()
        .domain([95, 115, 135, 155, 175, 195])
        .range(["#0a0", "#6c0", "#ee0", "#eb4", "#eb9", "#fff"]);

    var xAxis = d3.svg.axis()
        .scale(x)
        .orient("bottom")
        .ticks(20);

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");

    var svg = d3.select("body").append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    d3.json("readme-heatmap.json", function(error, heatmap) {
        if (error) throw error;

        var dx = heatmap[0].length,
            dy = heatmap.length;

        x.domain([0, dx]);
        y.domain([0, dy]);

        svg.selectAll(".isoline")
            .data(color.domain().map(isoline))
            .enter().append("path")
            .datum(function(d) { return d3.geom.contour(d).map(transform); })
            .attr("class", "isoline")
            .attr("d", function(d) { return "M" + d.join("L") + "Z"; })
            .style("fill", function(d, i) { return color.range()[i]; });

        svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis);

        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis);

        function isoline(min) {
            return function(x, y) {
                return x >= 0 && y >= 0 && x < dx && y < dy && heatmap[y][x] >= min;
            };
        }

        function transform(point) {
            return [point[0] * width / dx, point[1] * height / dy];
        }
    });

</script>