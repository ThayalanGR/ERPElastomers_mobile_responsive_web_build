<?php
/*
	Copyright mydbr.com 2007-2015 http://www.mydbr.com
	You are free to modify this file
*/


function Ext_HereIcon( $url, $bubble )
{
  $bubble_option = '';
  if (!is_null($bubble)) {
    $bubble_option= 'text: "'.$bubble.'"';
  }

  if ($url=='') {
    return array('standard', '{'.$bubble_option.'}');
  }
  if (substr($url, 0,4)=='http' || strpos($url,'.png')>0) {
    if ($point_pos = strpos($url, '::')) {

      $point = substr($url, $point_pos+2);

      $url = substr($url, 0, $point_pos);
    }
    return array('marker',',{visibility: true, icon:"'.$url.'",anchor: new nokia.maps.util.Point('.$point.')}');
  }
  if (substr($url, -8)=='-pushpin') {
    $url = substr($url, 0, strlen($url)-8);
  }
  if (substr($url, -4)=='-dot') {
    $url = substr($url, 0, strlen($url)-4);
  }
  if ($url=='pink') {
    $url = '#D6348C';
  }
  if ($bubble_option) $bubble_option .= ',';
  return array('standard', ',{'.$bubble_option.'brush:"'.$url.'"}');
}
/*
	This is the function described in extensions.php
	$id: object id in the report
	$options: command options described in extensions.php cmds, contains the parsed values from the report
	$dataIn: the 2-dim data array in
	$colInfo: column info - not needed in Google Maps
*/
function Ext_HereMaps( $id, $options,  $dataIn, $colInfo )
{
  global $mydbr_defaults;


  if (isset($mydbr_defaults['heremaps']['appId']) && isset($mydbr_defaults['heremaps']['authenticationToken'])) {
    $appId = $mydbr_defaults['heremaps']['appId'];
    $authenticationToken = $mydbr_defaults['heremaps']['authenticationToken'];
  } else {
    $appId = '0SIZ0phlVfMkgowhEe5o';
    $authenticationToken = 't5MxynA21vKBhocvHo133g';
  }


  // Default size of the map
  $defWidth = 800;
  $defHeight = 600;

  // Zeros will cause automatic centering using markers
  $autoMapX = 0;
  $autoMapY = 0;
  // Zero will cause automatic zooming around markers
  $autoZoom = 0;

  $showtext_in_bubble = 0;
  if (isset($options['dbr.heremaps.showtext']['enabled'])) {
    $showtext_in_bubble = 1;
  }


  // dbr.heremaps extension has 2 possible modes: coordinates and address. Lets make sure report has defined one.
  $mode = isset($options['dbr.heremaps']['mode']) ? $options['dbr.heremaps']['mode'] : '';

  if (!in_array($mode, array('coordinates', 'address'))) {
    error_print("Usage: select 'dbr.heremaps', 'coordinates | address' {,title, width, height, x, y, zoom}");
    return;
  }

  // Does the report define map dimensions?
  $width = isset($options['dbr.heremaps']['width']) ? intval($options['dbr.heremaps']['width']) : $defWidth;
  $height = isset($options['dbr.heremaps']['height']) ? intval($options['dbr.heremaps']['height']) : $defHeight;

  // Does the report define map center location?
  $x = isset($options['dbr.heremaps']['x']) ? floatval($options['dbr.heremaps']['x']) : $autoMapX;
  $y = isset($options['dbr.heremaps']['y']) ? floatval($options['dbr.heremaps']['y']) : $autoMapY;
  $zoom = isset($options['dbr.heremaps']['zoom']) ? intval($options['dbr.heremaps']['zoom']) : $autoZoom;

  // Automatic map center?
  $noXY = ($x==0 && $y==0) ? 'true' : 'false';

  // Automatic zoom?
  $noZoom = ($zoom == 0) ?  'true' : 'false';

  // Placeholder for the map, executed JavaScript will replace the content of this with the map
  // Ext_KeepWithNext keeps the map at same line as the previous element if so wanted (dbr.keepwithnext)
  echo '<div'.Extension::keepwithnext().'>';

  // If the title attribute has been set we'll use Subtitle class to draw it
  if (isset($options['dbr.heremaps']['title'])) {
    echo '<div class="Subtitle" style="text-align: center;padding-bottom:2px;">'. $options['dbr.heremaps']['title'] . '</div>';
  }
  echo '<div id="map'.$id.'" style="margin-left:auto;margin-right:auto;border:thin solid black; width:'.$width.'px; height:'.$height.'px"></div>';
  echo '</div>';
  echo '<script>';

  echo 'nokia.Settings.set( "appId", "'.$appId.'");';
  echo 'nokia.Settings.set( "authenticationToken", "'.$authenticationToken.'");';

  echo "var ext_infoBubbles$id = new nokia.maps.map.component.InfoBubbles();";
  echo "var ext_hm_map$id=HM_initMap($id,$x,$y,$zoom,ext_infoBubbles$id);";
  echo "var ext_hm_points$id = new Array();";
  echo "var ext_hm_addresses$id = new Array();";
  echo "var ext_hm_bubbles$id = new Array();";
  echo "var HM_click = HM_get_click();";

  $notFound = array();

  $addresses = array();
  echo "var HM_container$id = new nokia.maps.map.Container();";

  for ($i=0; $i<sizeof($dataIn) ; $i++) {
    $icon = '';
    $iconShadow = '';
    $ok = true;
    switch ($mode) {
      case 'address':
        $bubble = '';
        if (sizeof($dataIn[$i])>1) {
          $bubble = $dataIn[$i][1];
        }
        $image = '';
        if (sizeof($dataIn[$i])>2) {
          $image = $dataIn[$i][2];
        }
        $addresses[] = array('address' => $dataIn[$i][0], 'bubble' => $bubble, 'image' => $image);

        break;
      case 'coordinates':
        // In this case we already have the coordinates, just place the markers
        $lat = $dataIn[$i][0];
        $long = $dataIn[$i][1];

        $bubble = str_replace( '"', '\"', $dataIn[$i][2]);
        $bubble = str_replace( "'", "\'", $bubble);

        $icon = '';
        $image = '';
        if (sizeof($dataIn[$i])>3) {
          $image = $dataIn[$i][3];
        } else {
          $image = 'blue-pushpin';
        }
        if ($ok) {
          list($marker_type, $icon) = Ext_HereIcon($image, $showtext_in_bubble ? $bubble : null);

          if ($marker_type=='standard') {
            $marker = 'nokia.maps.map.StandardMarker';
          } else {
            $marker = 'nokia.maps.map.Marker';
          }
          echo "var marker = new $marker( new nokia.maps.geo.Coordinate($lat,$long)$icon);";
          if (!$showtext_in_bubble) {
            echo "marker = HM_add_marker_bubble(marker, '$bubble', ext_infoBubbles$id);";
          }
          echo "HM_container$id.objects.add( marker );";
        }

        break;
    }
  }
  if ($mode=='coordinates') {
    echo "ext_hm_map$id.objects.add(HM_container$id);";
    echo "ext_hm_map$id.zoomTo(HM_container$id.getBoundingBox(),false,true)";
  } else {
    echo "ext_hm_addresses$id = [";
    for ($i=0; $i < sizeof($addresses); $i++) {
      echo ($i>0 ? ',' : '') . '"'.$addresses[$i]['address'].'"';
    }
    echo "];";
    echo "ext_hm_bubbles$id = [";
    for ($i=0; $i < sizeof($addresses); $i++) {
      echo ($i>0 ? ',' : '') . '"'.$addresses[$i]['bubble'].'"';
    }
    echo "];";

    echo "HM_geocode(ext_hm_addresses$id, ext_hm_bubbles$id, ext_infoBubbles$id, HM_container$id, ext_hm_map$id)";
  }

  echo '</script>';


  if (sizeof($notFound)>0) {
    echo '<div class="comment">Google Maps was not able to locate following addresses:<br>';
    foreach ($notFound as $missing) {
      echo '- '.$missing.'<br>';
    }
    echo '</div>';
  }
}
