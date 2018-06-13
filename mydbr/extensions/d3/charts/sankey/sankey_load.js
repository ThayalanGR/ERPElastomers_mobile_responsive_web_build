$(document).ready(function() {
  if (typeof sankey_datas !== "undefined") {
    for (var i_sankey_index = 0; i_sankey_index < sankey_datas.length; i_sankey_index++) {
      sankey_data = sankey_datas[i_sankey_index];

      dragmove = function(d) {
        d3.select(this).attr("transform", "translate(" + d.x + "," + (d.y = Math.max(0, Math.min(height - d.dy, d3.event.y))) + ")");
        sankey.relayout();
        link.attr("d", path);
      }
      var top_margin = 0;
      if (sankey_data.title) {
        top_margin = 15;
      }

      var margin = {top: 1+top_margin, right: 1, bottom: 6, left: 1},
          width = sankey_data.size.x - margin.left - margin.right,
          height = sankey_data.size.y - margin.top - margin.bottom;

      var formatNumber = d3.format(sankey_data.format.number),
          format = function(d) { return formatNumber(d) + sankey_data.format.suffix; },
          color = d3.scale.category20();

      var svg = d3.select('#'+sankey_data.object).append("svg")
          .attr("width", width + margin.left + margin.right)
          .attr("height", height + margin.top + margin.bottom)
          .append("g")
          .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      if (sankey_data.title) {
        svg.append("text")
            .attr("x", (width / 2))             
            .attr("y", 0 - (margin.top / 2) + 3)
            .attr("class", "d3_title")
            .attr("text-anchor", "middle")  
            .text(sankey_data.title);
      }

      var sankey = d3.sankey()
          .nodeWidth(15)
          .nodePadding(10)
          .size([width, height]);

      var path = sankey.link();

      sankey
          .nodes(sankey_data.data.nodes)
          .links(sankey_data.data.links)
          .layout(32);

      var link = svg.append("g").selectAll(".link")
          .data(sankey_data.data.links)
          .enter().append("path")
          .attr("class", "link")
          .attr("d", path)
          .style("stroke-width", function(d) { return Math.max(1, d.dy); })
          .sort(function(a, b) { return b.dy - a.dy; });
      
      
      link.append("title")
          .text(function(d) { return d.source.name + " → " + d.target.name + "\n" + format(d.value); });
          
      link.on('click' , function(d){
        if (d.url.substr(0,10)=='javascript') {
          eval(d.url);
        } else if (d.url.substr(0,4)=='http') {
          window.location = d.url;
        }
      });

      var node = svg.append("g").selectAll(".node")
          .data(sankey_data.data.nodes)
          .enter().append("g")
          .attr("class", "node")
          .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
          .call(d3.behavior.drag()
          .origin(function(d) { return d; })
          .on("dragstart", function() { this.parentNode.appendChild(this); })
          .on("drag", dragmove));

      node.append("rect")
          .attr("height", function(d) { return d.dy; })
          .attr("width", sankey.nodeWidth())
          .style("fill", function(d) { 
            if (typeof sankey_data.label_colors[d.name] !== 'undefined') {
              d.color = sankey_data.label_colors[d.name];
              return d.color;
            }
            var id = parseInt(d.id, 10)-1;
            if (typeof sankey_data.colors[id] !== 'undefined') {
              return d.color = sankey_data.colors[id];
            }
            return d.color = color(d.name.replace(/ .*/, "")); })
          .style("stroke", function(d) { return d3.rgb(d.color).darker(2); })
          .append("title")
          .text(function(d) { return d.name + "\n" + format(d.value); });

      node.append("text")
          .attr("x", -6)
          .attr("y", function(d) { return d.dy / 2; })
          .attr("dy", ".35em")
          .attr("text-anchor", "end")
          .attr("transform", null)
          .text(function(d) { return d.name; })
          .filter(function(d) { return d.x < width / 2; })
          .attr("x", 6 + sankey.nodeWidth())
          .attr("text-anchor", "start");
    }
  }
  sankey_datas = [];
});