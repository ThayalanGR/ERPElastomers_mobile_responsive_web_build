/*
	Copyright mydbr.com http://www.mydbr.com
	You are free to modify this file
*/


/*
	Initializing the new map
*/
function HM_initMap( id, x, y, zoom, infoBubbles ) {
	
	var map = new nokia.maps.map.Display(document.getElementById("map"+id), 
	{

	    components: [ new nokia.maps.map.component.Behavior(),
	                new nokia.maps.map.component.ZoomBar(),
	                new nokia.maps.map.component.Overview(),                             
	                new nokia.maps.map.component.TypeSelector(),     
	                new nokia.maps.map.component.ScaleBar(),
					infoBubbles,
					],
	    'zoomLevel': zoom
	});

	map.setCenter (new nokia.maps.geo.Coordinate(x, y));
	
	return map;
}

var HM_onAllMarkersFinished = function(map, container) {     
 
		//we get the bounding box of the container         
		var bbox = container.getBoundingBox();         
 
		// if the bounding box is null then there are no objects inside         
		// meaning no markers have been added to it         
 
		if (bbox != null) {             
				// we have at least one address mapped             
				// so we add the container and zoomTo it             
 
				map.objects.add(container);
				map.zoomTo(container.getBoundingBox(),false,true);
		}     
};    

function HM_get_click()
{
	var TOUCH = nokia.maps.dom.Page.browser.touch,
		CLICK = TOUCH ? "tap" : "click";
		
	return CLICK;
}

function HM_add_marker_bubble( marker, bubbletext, infoBubbles )
{
	if (bubbletext!='') {
		marker.addListener(
			HM_get_click(), 
			function (evt) { 
				// Set the tail of the bubble to the coordinate of the marker
				infoBubbles.openBubble(bubbletext, marker.coordinate);
			}
		);
	}
	return marker;
}

var HM_onSearchComplete = function (data, requestStatus, infoBubble, bubbletext, container) {
	   if (requestStatus == "OK") {              
		    // if we are finished, we add a marker for the mapped position 
		    var marker = new nokia.maps.map.StandardMarker(data.location.position);
		    marker = HM_add_marker_bubble( marker, bubbletext, infoBubble );
		    container.objects.add(marker);
 
		    //increment the counter to notify another manager has finished             
		}
};


function HM_geocode( addresses, bubbles, infoBubble, container, map )
{
	var  i = 0 , 
		len = addresses.length,
		requests = addresses.length
		processed_id = i;
	
	$.each( addresses, function(index, value) {
		var state = value, capital = bubbles[index];
		
		nokia.places.search.manager.geoCode({
			searchTerm: state,
			onComplete: function (data, requestStatus) {
				HM_onSearchComplete(data, requestStatus, infoBubble, capital, container);
				requests--;
				if (requests == 0) {
					HM_onAllMarkersFinished(map, container);
				}
			}
		});
	});
}

