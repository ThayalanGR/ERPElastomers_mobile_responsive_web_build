<?php
/*
    Copyright mydbr.com 2007-2015 http://www.mydbr.com
    You are free to modify this file
*/
if (!defined('CWD')) {
  define ( "CWD", dirname( __FILE__ ) );
}

abstract class Ext_d3js {
  public $sources = array();
  public $targets = array();
  public $id;
  public $options;
  public $dataIn;
  public $colInfo;

  static function create_chart( $id, $options,  $dataIn, $colInfo )
  {
    $chart = null;
    switch ($options['dbr.d3']['chart']) {
      case 'sankey':
        include_once CWD.'/charts/sankey/sankey.class.php';
        $chart = new Ext_d3Sankey($id, $options,  $dataIn, $colInfo);
        break;
      case 'chord':
        include_once CWD.'/charts/chord/chord.class.php';
        $chart = new Ext_d3Chord($id, $options,  $dataIn, $colInfo);
        break;
    }
    if ($chart) {
      $chart->create();
    }
  }
}

function Ext_d3($id, $options,  $dataIn, $colInfo )
{
  Ext_d3js::create_chart( $id, $options,  $dataIn, $colInfo );
}
