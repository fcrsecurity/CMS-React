/* 
 * Implementation for Google Map API
 */
function initMap( lat, lng, zoom ) {	
		
	// If website is wider than 480px, isDraggable = false, else isDraggable = false
	var isDraggable = $(document).width() > 480 ? false : false;

	var mapOptions = {
		// How zoomed in you want the map to start at (always required)
		zoom: zoom,
		draggable: isDraggable,
		scrollwheel: false,

		// The latitude and longitude to center the map (always required)
		center: new google.maps.LatLng(lat, lng),

		// How you would like to style the map.
		// This is where you would paste any style found on Snazzy Maps.
		styles: [{
				"featureType": "all",
				"elementType": "all",
				"stylers": [{
						"hue": "#e7ecf0"
					}]
			}, {
				"featureType": "poi",
				"elementType": "all",
				"stylers": [{
						"visibility": "off"
					}]
			}, {
				"featureType": "road",
				"elementType": "all",
				"stylers": [{
						"saturation": -70
					}]
			}, {
				"featureType": "transit",
				"elementType": "all",
				"stylers": [{
						"visibility": "off"
					}]
			}, {
				"featureType": "transit.line",
				"elementType": "all",
				"stylers": [{
						"visibility": "on"
					}, {
						"color": "#449cd5"
					}]
			}, {
				"featureType": "transit.station",
				"elementType": "all",
				"stylers": [{
						"visibility": "on"
					}]
			}, {
				"featureType": "transit.station.rail",
				"elementType": "all",
				"stylers": [{
						"visibility": "on"
					}, {
						"hue": "#009cff"
					}]
			}, {
				"featureType": "transit.station.rail",
				"elementType": "geometry.fill",
				"stylers": [{
						"visibility": "on"
					}, {
						"hue": "#00fff5"
					}]
			}, {
				"featureType": "transit.station.rail",
				"elementType": "geometry.stroke",
				"stylers": [{
						"visibility": "simplified"
					}, {
						"hue": "#00ffcd"
					}]
			}, {
				"featureType": "transit.station.rail",
				"elementType": "labels.text.fill",
				"stylers": [{
						"visibility": "on"
					}, {
						"color": "#ffffff"
					}]
			}, {
				"featureType": "transit.station.rail",
				"elementType": "labels.text.stroke",
				"stylers": [{
						"visibility": "on"
					}, {
						"color": "#626264"
					}]
			}, {
				"featureType": "transit.station.rail",
				"elementType": "labels.icon",
				"stylers": [{
						"visibility": "on"
					}]
			}, {
				"featureType": "water",
				"elementType": "all",
				"stylers": [{
						"visibility": "simplified"
					}, {
						"saturation": -60
					}]
			}]
	};

	// Get the HTML DOM element that will contain your map
	var mapElement = document.getElementById('google_map');
    if (undefined !== mapElement) {
        // Create the Google Map using our element and options defined above
        var map = new google.maps.Map(mapElement, mapOptions);

        // Marker Image
        var image = {
            url: 'https://fcr.ca/uploads/icons/map_pin@2x.png',
            scaledSize: new google.maps.Size(22, 34)
        };

        // Let's also add a marker while we're at it
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            icon: image,
            map: map,
            animation: google.maps.Animation.DROP
        });
    }
}


