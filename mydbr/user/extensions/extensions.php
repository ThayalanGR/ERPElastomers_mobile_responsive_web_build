<?php

/*
Plugins are defined in an array containing following items;

Each extension is an array element with key defining the directory where the extension files are

'name'             = name of the extension
'enabled'          = enable / disable extensions. When disabled the commands are unavailable and the javascript will not be included in <HEAD>
'autoload'         = 1 = availability is based on 'enabled'-flag, 0 = load the extension and js & css for a report only when defined in report metadata and 'enabled' is true
'php'              = php-file to define the extension behavior
'css'              = if the extension needs to load css in <HEAD> section of the report, array() if not
'row_by_row_initialization' = Function to be called to initialize the extension prior any data is fecthed
'row_by_row_data_row' = Handle the incoming data
'row_by_row_finish' = All data is fecthed, finalize the actions
'single_pass_call' = Function to be called when the data is available (will be called with id, options and data)
'javascript'       = if the extension needs to load javascript in <HEAD> section of the report, array() if not
'cmds'             = myDBR commands defined for the extension, must have at least one which's 'cmd' parameter defines the actual command
	                 other cmds array elements define the parameters and if the parameter is obligatory (1) or not (0)
*/

$dbr_extensions['echart'] = (
array(
  'name' => 'Extended chart',
  'enabled' => true, // Set to false in mdbr
  'autoload' => 1,
  'php' => 'echart.php',
  'row_by_row_initialization' => '',
  'row_by_row_data_row' => '',
  'row_by_row_finish' => '',
  'single_pass_call' => 'Ext_EChart',
  'javascript' => array(),
  'css' => array(),
  'cmds' => array(
    array(
      'cmd' => 'dbr.echart',
      'type' => 1,
      'name' => 1,
    ),
    array(
      'cmd' => 'dbr.echart.color',
      'color0' => 1,
      'color1' => 0,
      'color2' => 0,
      'color3' => 0,
      'color4' => 0,
      'color5' => 0,
      'color6' => 0,
      'color7' => 0,
      'color8' => 0,
      'color9' => 0,
      'color10' => 0,
    ),
    array(
      'cmd' => 'dbr.echart.name',
      'name0' => 1,
      'name1' => 0,
      'name2' => 0,
      'name3' => 0,
      'name4' => 0,
      'name5' => 0,
      'name6' => 0,
      'name7' => 0,
      'name8' => 0,
      'name9' => 0,
      'name10' => 0,
    ),
    array(
      'cmd' => 'dbr.echart.bubble_scale',
      'scale' => 1
    )
  )
));
