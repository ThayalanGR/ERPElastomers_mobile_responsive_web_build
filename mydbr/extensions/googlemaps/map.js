/*
  Copyright mydbr.com http://www.mydbr.com
  You are free to modify this file
*/


/*
  Initializing the new map
*/

function GM_initMap( id, x, y, zoom, type ) {
  var map_type, latlng = new google.maps.LatLng(x, y);
  switch (type) {
    case 'satellite':
      map_type = google.maps.MapTypeId.SATELLITE;
      break;
    case 'terrain':
      map_type = google.maps.MapTypeId.TERRAIN;
      break;
    case 'hybrid':
      map_type = google.maps.MapTypeId.HYBRID;
      break;
    default:
      map_type = google.maps.MapTypeId.ROADMAP;
      break;
  }
  
  var options = {
    zoom: zoom,
    center: latlng,
    mapTypeControl: true,
      mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
      navigationControl: true,
      mapTypeId: map_type
  };
  
  var map = new google.maps.Map(document.getElementById('map'+id), options);
  
  return map;
}



/*
  Place a new marker to map. 
  noXY and noZoom will determine if the center and the zoom is done automatically or if the user set them
  
  iconFile can contain preinstalled icons (see markers and pushpins) or any image file reference with size/anchor position added to end (http://mypics.com/image.png:32:32::center)
*/
function GM_newMarker( points_arr, map, lat, lon, desc, url, new_window, noXY, noZoom, iconFile, shadowFile, markerLabel ){
  var marker, newicon, markerInd, pushpinInd, iconSize;
  var markerbase = 'http://maps.google.com/mapfiles/ms/micons/';
  var markerOptions, image, icon_x, icon_y, icon_pos;
  
  // Inlcuded markers & pushpins
  var markers = [ 'blue', 'green', 'orange', 'pink', 'purple', 'red', 'yellow', 'blue-dot', 'green-dot', 'orange-dot', 'pink-dot', 'purple-dot', 'red-dot', 'yellow-dot' ];
  var pushpins = [ 'blue-pushpin', 'grn-pushpin', 'pink-pushpin', 'purple-pushpin', 'red-pushpin', 'yellow-pushpin' ];
  
  var point = new google.maps.LatLng(lat,lon);
  
  
  points_arr.push(point);
  if (iconFile!=='') {
    if ( (markerInd = jQuery.inArray(iconFile, markers))>=0 ||
       (pushpinInd = jQuery.inArray(iconFile, pushpins))>=0) {
      
      iconFile = markerbase+iconFile+".png";
      iconSize = google.maps.Size(32, 32);
    } else {
      var iconInfo = iconFile.split('::');
      if (iconInfo.length>=3 && iconInfo[0]!=='') {
        iconFile = iconInfo[0];
        icon_x = parseInt(iconInfo[1], 10);
        icon_y = parseInt(iconInfo[2], 10);
        iconSize = google.maps.Size( parseInt(iconInfo[1], 10), parseInt(iconInfo[2], 10));
        if (iconInfo.length>3) {
          icon_pos = iconInfo[3];
        }
      }
    }
    image = {
      url: iconFile,
      anchor: icon_pos == 'center' ? new google.maps.Point(icon_x/2, icon_y/2) : null, 
      scaledSize: icon_x && icon_y ? new google.maps.Size(icon_x, icon_y) : null
    }
  }
  marker= new google.maps.Marker({
    position: point,
    map: map,
    icon: image,
    label: markerLabel,
    url: url,
    new_window: new_window
  });

  var infowindow = new google.maps.InfoWindow();
  google.maps.event.addListener(marker, 'click', function() {
    if (marker.url) {
      if (new_window) {
        window.open(marker.url);
      } else {
        document.location.href = marker.url;
      }
    } else {
      infowindow.setContent(desc);
      infowindow.open(map,marker);
    }
  });
}

function GM_setBounds( map, points, noXY, noZoom, zoom )
{
  var i;
  
  if (noXY || noZoom ) {
    var bounds = new google.maps.LatLngBounds();

    for (i=0; i < points.length; i++) {
        var ll = new google.maps.LatLng(points[i].lat(), points[i].lng());
        bounds.extend(ll);
    }
    
    if (points.length==1 && map.getZoom()===0) {
      if (noXY) {
        map.setCenter(bounds.getCenter());
      }
      if (noZoom) {
        map.setZoom(7);
      }
    } else {
      map.fitBounds(bounds);
    }
  }
}

function GM_fix_zoom(map, zoom) 
{
  var listener = google.maps.event.addListener(map, "idle", function() {
    if (map.getZoom() !== zoom) map.setZoom(zoom);
      google.maps.event.removeListener(listener);
  });
}