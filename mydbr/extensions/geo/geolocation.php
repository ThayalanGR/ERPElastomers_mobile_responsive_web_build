<?php
function Geo_GetLoc($ipaddress)
{
  global $geo_license_key;

  $data = array();
  $license_key=$geo_license_key;
  $query = "http://geoip1.maxmind.com/f?l=" . $license_key . "&i=" . $ipaddress;
  $url = parse_url($query);
  $host = $url["host"];
  $path = $url["path"] . "?" . $url["query"];
  $timeout = 1;
  $buf = '';
  $fp = fsockopen ($host, 80, $errno, $errstr, $timeout);
  if ($fp) {
    fputs ($fp, "GET $path HTTP/1.0\nHost: " . $host . "\n\n");
    while (!feof($fp)) {
      $buf .= fgets($fp, 128);
    }
    $lines = preg_split("/\n/", $buf);
    $data = $lines[count($lines)-1];
    fclose($fp);
  }
  else {
    echo '<div class="comment">MaxMind was not able to locate addresses: '.$ipaddress.'<br>';
  }
  $arr = explode(",", $data);
  if (isset($arr[8])) {
    $arr[8] = substr($arr[8],1,-1);
  }
  if (isset($arr[9])) {
    $arr[9] = substr($arr[9],1,-1);
  }
  return $arr;
}


function Geo_GetLocations( $id, $options,  $dataIn, $colInfo )
{
  global $geo_license_key;
  if (!isset($geo_license_key)) {
    echo '<div>MaxMind license key is not set</div>';
  }

  $tSQLCmd = 'sp_Geo_ip_getnew';
  $noloc = SQL_Fetch($tSQLCmd);

  for ($i=0; $i < sizeof($noloc); $i++) {
    $ip = trim($noloc[$i][0]);
    $loc = Geo_GetLoc($ip);
    if (isset($loc[4]) && ($loc[4]!='')) {
      $done = true;
      $tSQLCmd = 'sp_Geo_location_add ' . 
        SQL_String($ip) . ','.      // IP
        SQL_String($loc[0]) . ','.  // country
        SQL_String($loc[1]) . ','.  // region
        SQL_String($loc[2]) . ','.  // city
        SQL_String($loc[4]) . ','.  // latituude
        SQL_String($loc[5]) . ','.  // longitude 
        SQL_String($loc[8]) . ','.  // ISP 
        SQL_String($loc[9]);

      $tSQLCmd = utf8_encode($tSQLCmd);
      $result = SQL_Fetch($tSQLCmd);
    }
  }
  if (sizeof($noloc)>0) {
    echo '<div>'.sizeof($noloc).' new address(es)</div>';
  }
}
