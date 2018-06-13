<?php

function Ext_OrgChart( $id, $options,  $dataIn, $colInfo )
{
  $chart_options = isset($chart_options['options']) ? $chart_options['options'] : '';

  /* This will add a drill menu in cases where multiple linked reports need a menu */
  Extension::add_drill_menu();

  $obj_id = 'org_chart'.$id;
  $canvas_id = 'org_chart_canvas'.$id;

  // lineColor: "#3388DD",      // Color of the connection lines (global for all lines)
  // lineWidth: 1,              // Connection line width
  // boxWidth: 0,               // Box width (global for all boxes)
  // boxHeight: 0,              // Box height (global for all boxes)
  // hSpace: 20,                // Horizontal space in between the boxes (global for all boxes)
  // vSpace: 20,                // Vertical space in between the boxes (global for all boxes)
  // hShift: 30,			      // The number of pixels vertical siblings are shifted (global for all boxes)
  // boxLineColor: "#7082EA",   // Default box line color
  // boxFillColor: "#CFE8EF",   // Default box fill color
  // boxPadding: 16,            // Default box padding
  // textColor: "#000000",	  // Default box text color
  // textFont: "arial",		  // Default font
  // textSize: 12,			  // Default text size (pixels, not points)
  // boxHeightMin: 0,           // Minimum height of the node
  // boxWidthMin: 0,            // Minimum width of the node
  // colorBoxHeight: 10,        // Default coloBox height
  // textVAlign: 1,             // Default text alignment
  // shadowOffsetX: 3,
  // shadowOffsetY: 3,
  // shadowColor: "#A1A1A1",
  // radius: 5,
  // roundRectBorderColor: "#003300"

  echo '<div class="organization_wrapper">'.
    '<div class="organization_target_tt"></div>'.
    '<canvas id="'.$canvas_id.'" style="margin-top:10px;margin-left:10px" width="0" height="0"></canvas>';
  if (isset($options['dbr.org.chart.export'])) {
    echo '<a class="org_chart_png" href="javascript:window.open(document.getElementById(\''.$canvas_id.'\').toDataURL(\'image/png\'))">'.loc('EXT_ORG_CHART_OPEN_AS_PNG').'</a>';
  }
  echo '</div>'.
    '<script>'.
    "\n".'var '.$obj_id.' = new orgChart('.$chart_options.');'.
    "\n".$obj_id.'.setSize(0,0);'."\n";

  for ($row=0; $row < sizeof($dataIn); $row++) {
    if (sizeof($dataIn[$row])<7) {
      $data_width = sizeof($dataIn[$row]);
      for ($i=0; $i < 7- $data_width; $i++) {
        $dataIn[$row][] = null;
      }
    }
    list($node_id, $parent_id, $direction, $name, $url, $color, $border_color ) = $dataIn[$row];

    if ($urls  = Extension::get_report_links_direct($id, $obj_id, $dataIn[$row])) {
      $url = $urls;
    }
    if ($color) {
      $color = "'".$color."'";
    } else {
      $color = 'null';
    }
    if ($border_color) {
      $border_color = "'".$border_color."'";
    } else {
      $border_color = 'null';
    }

    echo $obj_id.'.addNode('.
      json_encode($node_id). ','.
      (is_null($parent_id) ? "''" : json_encode($parent_id)). ','.
      "'".$direction."',".
      json_encode($name).','.
      '0,'. // No double-width border will be drawn
      '"'.$url.'",'.
      $border_color.','.
      $color.");\n";
  }

  $scores = array();
  if (isset($options['dbr.org.target'])) {
    $target = $options['dbr.org.target'];
    global $log;
    $log->info($target);
    for ($i=0; $i < sizeof($target['node_id']) ; $i++) {
      if ( sizeof($target['node_id']) == 1) {
        $node_id = $target['node_id'];
        $weight = $target['weight'];
        $score = is_null($target['score']) ? '0': $target['score'];
        $color = $target['color'];
        $name = $target['name'];
      } else {
        $node_id = $target['node_id'][$i];
        $weight = $target['weight'][$i];
        $score = isset($target['score'][$i]) ? $target['score'][$i] : '0';
        $color = $target['color'][$i];
        $name = $target['name'][$i];
      }
      echo $obj_id.'.addColorBox('.$node_id.','.$weight.','.$score.',"'.$color.'",'.json_encode($name).');'."\n";
    }
  }
  echo '$(document).ready(function() {'.
    $obj_id.'.drawChart("'.$canvas_id.'");'."\n".
    $obj_id.'.drawColorBox();'."\n".
    '});';
  echo '</script>';

  // echo $obj_id.'.drawChart("'.$canvas_id.'");'."\n";
  // echo $obj_id.'.drawColorBox();'."\n";

}