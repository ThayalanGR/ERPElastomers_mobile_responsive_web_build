
orgChart.prototype.addCircle = function ( ctx, id, color, i, num )
{
var vpos, hpos, item_pos;

vpos = o.getNodeByID(id).vpos+36;
hpos = o.getNodeByID(id).hpos+(o.getBoxSize().width/2);
item_pos = (num-1)*14/2 - (i-1)*14;

ctx.fillStyle = color;
ctx.beginPath();
ctx.arc(hpos-item_pos, vpos, 5, 0, Math.PI*2, true);
ctx.closePath();
ctx.lineWidth = 1;
ctx.strokeStyle = "#003300";
ctx.stroke();
ctx.fill();
};

function calc_target_widths( max_width, values )
{
  var start = 0, ret = [], total_weight = 0, i, width = max_width;
  
  for (i=0; i < values.length; i++) {
    total_weight += values[i].weight;
  }

  for (i=0; i < values.length; i++) {
    percent = values[i].weight / total_weight;
    
    w = Math.round(width * percent,0);
    
    ret[i] = [start,w];
    start += w + 2;
    total_weight -= values[i].weight;
    width -= (w + 2);
  }
  
  return ret;
}


