<?php

// Ext_EChartC initializes the object and provides a factory method create_chart_and_print 
// To add new chart types extend Ext_EChartC with new classs and add selection to create_chart_and_print 
abstract class Ext_EChartC {
  protected $id;
  protected $c;
  protected $options;
  protected $dataIn;
  protected $colInfo;
  protected $title_font;
  protected $title_font_size;
  protected $axis_font;
  protected $axis_font_size;
  protected $x;
  protected $y;

  function setup( $id, $options,  $dataIn, $colInfo )
  {
    global $mydbr_defaults;

    $this->id = $id;
    $this->options = $options;
    $this->dataIn = $dataIn;
    $this->colInfo = $colInfo;

    // Using global settings
    $this->title_font = $mydbr_defaults['chart']['title_font'];
    $this->title_font_size = $mydbr_defaults['chart']['title_font_size'];
    $this->axis_font = $mydbr_defaults['chart']['axis_font'];
    $this->axis_font_size = $mydbr_defaults['chart']['axis_font_size'];
  }

  static function create_chart_and_print( $id, $options,  $dataIn, $colInfo )
  {
    $chart = null;
    switch ($options['dbr.echart']['type']) {
      case 'radar':
      case 'polar':
        $chart = new Ext_PolarChart();
        break;
      case 'bubble':
        $chart = new Ext_BubbleChart();
        break;
    }
    if ($chart) {
      $chart->setup( $id, $options,  $dataIn, $colInfo );
      $chart->create();
      $chart->print_chart();
    }
  }

  function print_chart()
  {
    global $mydbr_defaults;

    $image_format = get_chart_image_format();
    $chart_director_format = chart_format_to_chartdirector_type( $image_format );
    if ($chart_director_format == SVG || $chart_director_format == SVGZ) {
      $this->c->enableVectorOutput();
    }
    if (Extension::image_embed()) {
      $url = 'data:image/'.image_data_uri_type($image_format).';base64,'.base64_encode($this->c->makeChart2($chart_director_format));
    } else {
      $url = 'lib/mydbr_get_cd_chart.php?'.str_replace("&","&amp;",$this->c->makeSession("chart" . $this->id, $chart_director_format));

    }
    if (($chart_director_format == SVG || $chart_director_format == SVGZ) && !Browser::is_ie()) {
      svg_object( $this->id, $url, '', $this->x, $this->y );
    }
    else {
      $size = '';
      if ($this->x) $size .= 'width="'.$this->x.'" ';
      if ($this->y) $size .= 'height="'.$this->y.'" ';
      
      echo '<img src="'.$url.'" alt="" '.$size.'>';
    }
  }

  abstract protected function create();
}

class Ext_PolarChart extends Ext_EChartC
{
  protected function create()
  {
    $this->x = 480;
    $this->y = 380;

    $this->c = new PolarChart($this->x, $this->y, 0xFFFFFF, 0xFFFFFF, 0);

    $this->c->addTitle($this->options['dbr.echart']['name'], $this->title_font, $this->title_font_size, 0x000000);

    $this->c->setPlotArea(240, 210, 150, 0xffffff);

    if (strtolower($this->options['dbr.echart']['type'])=='polar') {
      $this->c->setGridStyle(false, false);
    }

    $b = $this->c->addLegend(470, 35, true, "arialbd.ttf", 10);
    $b->setAlignment(TopRight);
    $b->setBackground(silverColor(), Transparent, 1);

    for ($i=0; $i < sizeof($this->dataIn); $i++) {
      $color = hexdec($this->options['dbr.echart.color']['color'.$i]);
      $this->c->addAreaLayer($this->dataIn[$i], $color, $this->options['dbr.echart.name']['name'.$i]);
      $lineLayerObj = $this->c->addLineLayer($this->dataIn[$i], $color);
      $lineLayerObj->setLineWidth(3);
    }

    $this->c->angularAxis->setLabels($this->colInfo['name']);
  }
}

class Ext_BubbleChart extends Ext_EChartC
{
  protected function create()
  {
    $bubbles = array();
    $dataX = array();
    $dataY = array();
    $dataZ = array();
    $datagroup = '';

    // Putting X, Y, X and segment name to own arrays
    $this->dataIn = array_transpose($this->dataIn);
    // Separate each segment to it's own array 
    foreach ($this->dataIn[3] as $key => $value) {
      if ($datagroup != $value) {
        if (sizeof($dataX)>0) {
          // Found new segment
          $bubbles[$datagroup] = array( 'x' => $dataX, 'y' => $dataY, 'z' => $dataZ );
          $dataX = array();
          $dataY = array();
          $dataZ = array();
        }
        $datagroup = $value;
      }
      $dataX[] = $this->dataIn[0][$key];
      $dataY[] = $this->dataIn[1][$key];
      $dataZ[] = $this->dataIn[2][$key];
    }
    // Last new segment
    if (sizeof($dataX)>0) {
      $bubbles[$datagroup] = array( 'x' => $dataX, 'y' => $dataY, 'z' => $dataZ );
    }

    $this->x = 450;
    $this->y = 450;

    $this->c = new XYChart($this->x, $this->y);
    $this->c->setPlotArea(55, 85, 330, 330, -1, -1, 0xc0c0c0, 0xc0c0c0, -1);

    $legendObj = $this->c->addLegend(50, 30, false, $this->title_font, $this->title_font_size);
    $legendObj->setBackground(Transparent);

    $this->c->addTitle($this->options['dbr.echart']['name'], $this->title_font, $this->title_font_size, 0x000000);

    if (isset($this->options['dbr.echart.bubble_scale']['scale'])) {
      $minx = null;
      $maxx = null;
      $min = null;
      $max = null;

      // We'll scale the chart
      foreach ($bubbles as $name => $values) {
        for ($i=0; $i <sizeof($values['x']) ; $i++) {
          if (is_null($min) || $values['x'][$i]-$values['z'][$i]/2<$min) {
            $min = $values['x'][$i]-$values['z'][$i]/2;
          }
          if (is_null($max) || $values['x'][$i]+$values['z'][$i]/2>$max) {
            $max = $values['x'][$i]+$values['z'][$i]/2;
          }
          if (is_null($min) || $values['y'][$i]-$values['z'][$i]/2<$min) {
            $min = $values['y'][$i]-$values['z'][$i]/2;
          }
          if (is_null(null) || $values['y'][$i]+$values['z'][$i]/2>$max) {
            $max = $values['y'][$i]+$values['z'][$i]/2;
          }
        }
      }

      $max = $max - ($max/10-round($max/10))*10;
      $min = $min + ($min/10-round($min/10))*10;

      $scale = ($max - $min)/10;

      $this->c->xAxis()->setLinearScale($min, $max, $scale);
      $this->c->yAxis()->setLinearScale($min, $max, $scale);
    }

    $this->c->xAxis->setLabelStyle( $this->axis_font, $this->axis_font_size );
    $this->c->yAxis->setLabelStyle( $this->axis_font, $this->axis_font_size );

    $this->c->xAxis->setWidth(3);
    $this->c->yAxis->setWidth(3);

    $i = 0;
    foreach ($bubbles as $name => $values) {
      $color = hexdec($this->options['dbr.echart.color']['color'.$i]);
      $scatterLayerObj = $this->c->addScatterLayer($values['x'], $values['y'], $name, CircleSymbol, 9, $color, $color);
      // Do the actual scaling
      if (isset($this->options['dbr.echart.bubble_scale']['scale'])) {
        $scatterLayerObj->setSymbolScale($values['z'], XAxisScale, array(), YAxisScale);
      } else {
        $scatterLayerObj->setSymbolScale($values['z']);
      }
      $i++;
    }
  }
}

function Ext_EChart( $id, $options,  $dataIn, $colInfo )
{
  Ext_EChartC::create_chart_and_print( $id, $options,  $dataIn, $colInfo );
}