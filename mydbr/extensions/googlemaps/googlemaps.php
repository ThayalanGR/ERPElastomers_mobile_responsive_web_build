<?php
/*
	Copyright mydbr.com 2007-2015 http://www.mydbr.com
	You are free to modify this file
*/


/*
	This will return the latitude and the longitude of a given address
*/

class GoogleMaps {
  static function URLsafe_b64decode($string)
  {
    $data = str_replace(array('-','_'),array('+','/'),$string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
      $data .= substr('====', $mod4);
    }
    return base64_decode($data);
  }

  static function URLsafe_b64encode($string)
  {
    $data = base64_encode($string);
    $data = str_replace(array('+','/'),array('-','_'),$data);
    return $data;
  }

  static function getLink($show_link_in_title, $links)
  {
    $url = '';
    $new_window = 'false';
    if (sizeof($links)==1 && !$show_link_in_title) {
      $url = $links[0][1];
      if ($links[0]['target']=='new_window') {
        $new_window = 'true';
      }
    }
    return array($url, $new_window);
  }

  static function BusinessURL( $url )
  {
    global $mydbr_defaults;

    if (isset($mydbr_defaults['chart']['google_maps_business'])) {
      $keys = $mydbr_defaults['chart']['google_maps_business'];
      if ($keys['client_id'] && $keys['private_key']) {
        $url .= '&client='.$keys['client_id'];
        $url .= '&signature='.GoogleMaps::URLsafe_b64encode(hash_hmac('sha1', $url, GoogleMaps::URLsafe_b64decode($keys['private_key']), true));
      }
    }
    return $url;
  }

  static function GetCoordinates( $address )
  {
    global $mydbr_defaults;

    $lat_long = array();
    $tryit = true;
    $delay = 0;
    $maxtry = 10; /* We'll try the query max 10 times */
    $lat_long[0] = 'NO_RESPONSE_FROM_GOOGLE'; /* In case we go over the limit */
    while($tryit && $maxtry>0) {
      $tryit = false;

      $url_start = 'http' . (is_secure_connection() ? 's' : '') . '://maps.googleapis.com';
      $url = $url_start . GoogleMaps::BusinessURL('/maps/api/geocode/json?address='.urlencode($address).'');

      $error = null;
      $json = get_url_content($url, $error, 20, true);
      if (!$error && $json) {
        $result = json_decode($json);
        if ($result->status == 'OK') {
          // OK, we got the location
          $lat_long[0] = $result->results[0]->geometry->location->lat;
          $lat_long[1] = $result->results[0]->geometry->location->lng;
        } elseif ($result->status == 'OVER_QUERY_LIMIT') {
          $tryit = true;
          $maxtry--;
          $delay += 100000;
          usleep($delay);
        } else {
          // There was an error, let's pass that to user
          $lat_long[0] = $result->status;
        }
      } else {
        if ($error) {
          $lat_long[0] = $error;
        }
        if ($json) {
          $lat_long[0] = $json;
        }

        // If the file_get_contents failed we will not get a result, we'll just try again (for few times)
        // Don't bother trying if Google said "403"
        if (!strpos($error, "403")) {
          usleep($delay);
          $maxtry--;
          $tryit = true;
        }
      }
    }
    return $lat_long;
  }
}

/*
	This is the function described in extensions.php
	$id: object id in the report
	$options: command options described in extensions.php cmds, contains the parsed values from the report
	$dataIn: the 2-dim data array in
	$colInfo: column info - not needed in Google Maps
*/
function Ext_GoogleMaps( $id, $options,  $dataIn, $colInfo )
{
  // Default size of the map
  $defWidth = 800;
  $defHeight = 600;

  // Zeros will cause automatic centering using markers
  $autoMapX = 0;
  $autoMapY = 0;
  // Zero will cause automatic zooming around markers
  $autoZoom = 0;

  $heatmap = false;
  $heatmap_options = '';

  if (isset($options['dbr.googlemaps.heatmap']['enabled'])) {
    $heatmap = true;
    if (isset($options['dbr.googlemaps.heatmap']['options'])) {
      $heatmap_options = ",".$options['dbr.googlemaps.heatmap']['options'];
    }
  }

  $show_link_in_title = false;
  
  if (isset($options['dbr.googlemaps.link_in_title']['enabled'])) {
    $show_link_in_title = $options['dbr.googlemaps.link_in_title']['enabled'];
  }
  // dbr.googlemaps extension has 2 possible modes: coordinates and address. Lets make sure report has defined one.
  $mode = isset($options['dbr.googlemaps']['mode']) ? $options['dbr.googlemaps']['mode'] : '';

  if (!in_array($mode, array('coordinates', 'address'))) {
    error_print("Usage: select 'dbr.googlemaps', 'coordinates | address' {,title, width, height, x, y, zoom}");
    return;
  }

  // Does the report define map dimensions?
  $width = isset($options['dbr.googlemaps']['width']) ? $options['dbr.googlemaps']['width'] : $defWidth;
  $height = isset($options['dbr.googlemaps']['height']) ? $options['dbr.googlemaps']['height'] : $defHeight;
  if (is_numeric(substr($width,-1))) {
    $width .= 'px';
  }
  if (is_numeric(substr($height,-1))) {
    $height .= 'px';
  }
  
  // Does the report define map center location?
  $x = isset($options['dbr.googlemaps']['x']) ? floatval($options['dbr.googlemaps']['x']) : $autoMapX;
  $y = isset($options['dbr.googlemaps']['y']) ? floatval($options['dbr.googlemaps']['y']) : $autoMapY;
  $zoom = isset($options['dbr.googlemaps']['zoom']) ? intval($options['dbr.googlemaps']['zoom']) : $autoZoom;
  $map_type = isset($options['dbr.googlemaps']['map_type']) ? $options['dbr.googlemaps']['map_type'] : 'roadmap';

  // Automatic map center?
  $noXY = ($x==0 && $y==0) ? 'true' : 'false';
  // Automatic zoom?
  $noZoom = ($zoom == 0) ?  'true' : 'false';

  // Placeholder for the map, executed JavaScript will replace the content of this with the map
  // Ext_KeepWithNext keeps the map at same line as the previous element if so wanted (dbr.keepwithnext)
  echo '<div '.Extension::resultclass(true, 'map_wrapper', true).'>';
  // If the title attribute has been set we'll use subtitle class to draw it
  if (isset($options['dbr.googlemaps']['title'])) {
    echo '<div class="subtitle" style="text-align: center;padding-bottom:2px;">'. $options['dbr.googlemaps']['title'] . '</div>';
  }
  echo '<div id="map'.$id.'" style="margin-left:auto;margin-right:auto;border:thin solid black; width:'.$width.'; height:'.$height.'">Generating the map...</div>';
  echo '</div>';
  echo '<script>';
  echo '//<![CDATA['."\n";
  // Initialize the map object 
  echo "var ext_gm_map$id=GM_initMap('$id',$x,$y,$zoom,'$map_type');";
  echo "var ext_gm_points$id = new Array();";
  // echo "var ext_gm_bounds$id = new GLatLngBounds();";
  $notFound = array();
  // Go through the data to set the markers (pins)
  $heatmap_points = '';
  $heatmap_locations = '';
  $heatmap_weighted = false;
  for ($i=0; $i<sizeof($dataIn) ; $i++) {
    
    $links = Extension::get_report_links($dataIn[$i], null, true);
    $icon = '';
    $iconShadow = '';
    $markerLabel = '';
    $ok = true;
    if ($i==0 && sizeof($dataIn[0])>2) {
      $heatmap_weighted = true;
    }
    switch ($mode) {
      case 'address':
        $coord = GoogleMaps::GetCoordinates($dataIn[$i][0]);
        if (sizeof($coord)==2) {
          // Got the address, place the marker
          $lat = $coord[0];
          $long = $coord[1];
          if ($heatmap) {
            if ($heatmap_weighted) $weight = $dataIn[$i][1];
          } else {
            $bubble = $dataIn[$i][1];
            if (sizeof($dataIn[$i])>2) {
              $icon = $dataIn[$i][2];
              if (sizeof($dataIn[$i])>3) {
                $iconShadow = $dataIn[$i][3];
                if (sizeof($dataIn[$i])>4) {
                  $markerLabel = $dataIn[$i][4];
                }
              }
            }
          }
        } else {
          $ok = false;
          $google_errorCode = '';
          if (sizeof($coord)>0) {
            $google_errorCode = ', '.$coord[0];
          }
          if ($dataIn[$i][0]) {
            array_push($notFound, $dataIn[$i][0].$google_errorCode);
          } else {
            array_push($notFound, 'Missing address for '.isset($dataIn[$i][1]) ? $dataIn[$i][1] : '');
          }
        }
        break;
      case 'coordinates':
        // In this case we already have the coordinates, just place the markers
        $lat = $dataIn[$i][0];
        $long = $dataIn[$i][1];
        if ($heatmap) {
          if ($heatmap_weighted) $weight = $dataIn[$i][2];
        } else {
          $bubble = $dataIn[$i][2];
          if (sizeof($dataIn[$i])>3) {
            $icon = $dataIn[$i][3];
            if (sizeof($dataIn[$i])>4) {
              $iconShadow = $dataIn[$i][4];
              if (sizeof($dataIn[$i])>5) {
                $markerLabel = $dataIn[$i][5];
              }
            }
          }
        }
        break;
    }
    if ($ok) {
      if ($heatmap) {
        if ($heatmap_weighted) {
          $weight = floatval($weight);
          $heatmap_locations .= "{location: new google.maps.LatLng($lat, $long), weight:$weight}";
          $heatmap_points .= "new google.maps.LatLng($lat, $long)";
        } else {
          $heatmap_points .= "new google.maps.LatLng($lat, $long)";
        }
        $comma = (($i==(sizeof($dataIn)-1)) ? '' : ',');
        $heatmap_points .= $comma;
        if ($heatmap_weighted) {
          $heatmap_locations .= $comma;
        }
      } else {
        $link_url = '';
        foreach ($links as $key => $link) {
          $link_url .= '<br><a href="'.$link[1].'">'.htmlentities($link[0]).'</a>';
        }
        list($url, $new_window) = GoogleMaps::getLink($show_link_in_title, $links);
        echo "GM_newMarker(ext_gm_points$id, ext_gm_map$id, $lat, $long, ".json_encode($bubble.$link_url).",".json_encode($url).", $new_window, $noXY, $noZoom, '$icon', '$iconShadow',".json_encode($markerLabel).");";
      }
    }
  }

  if ($heatmap) {
    if ($heatmap_weighted) {
      echo "var ext_gm_locations$id = [" . $heatmap_locations  . '];';
    }
    echo "var ext_gm_points$id = [" . $heatmap_points  . '];'.
      "var heatmap$id = new google.maps.visualization.HeatmapLayer({ data: ".
      ($heatmap_weighted ? "ext_gm_locations$id" : "ext_gm_points$id").
      " $heatmap_options });".
      "heatmap$id.setMap(ext_gm_map$id);";
  }
  echo "GM_setBounds(ext_gm_map$id, ext_gm_points$id, $noXY, $noZoom);";
  echo "if (!$noZoom) { GM_fix_zoom(ext_gm_map$id, $zoom); }";

  echo '//]]>'."\n";
  echo '</script>';
  if (sizeof($notFound)>0) {
    echo '<div class="comment">Google Maps was not able to locate following addresses:<br>';
    foreach ($notFound as $missing) {
      echo '- '.$missing.'<br>';
    }
    echo '</div>';
  }
}
