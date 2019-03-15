/* 
 * Implementation for Google Map API
 */
 var marker;
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
	var mapElement = document.getElementById('google_map_contact');
	// Create the Google Map using our element and options defined above
	var map = new google.maps.Map(mapElement, mapOptions);
	
	// Marker Image
	var image = {
        url:'https://fcr.ca/uploads/icons/map_pin@2x.png',
		scaledSize: new google.maps.Size(22, 34)
	};

	// Let's also add a marker while we're at it
	marker = new google.maps.Marker({
		position: new google.maps.LatLng(lat, lng),
		icon: image,
		map: map,
		animation: google.maps.Animation.DROP
	});

	if (document.getElementById('accept-changes-map')){
		var geocoder = new google.maps.Geocoder();
	    document.getElementById('accept-changes-map').addEventListener('click', function() {
	      geocodeAddress(geocoder, map);
	    });
	    geocodeLatLng(geocoder, map);
	}
}
function geocodeAddress(geocoder, resultsMap) {
    var address = document.getElementById('address').value;
    geocoder.geocode({'address': address}, function(results, status) {
      if (status === 'OK') {
        resultsMap.setCenter(results[0].geometry.location);
        document.getElementById('input-lat').value = results[0].geometry.location.lat();
        document.getElementById('input-lng').value = results[0].geometry.location.lng();
         pushNewLatLng(document.getElementsByClassName('google_map_contact_cls')[0].getAttribute("data-id"),results[0].geometry.location.lat(),results[0].geometry.location.lng());
		var image = {
        	url:'https://fcr.ca/uploads/icons/map_pin@2x.png',
        	scaledSize: new google.maps.Size(22, 34)
		};
		marker.setMap(null);
        marker = new google.maps.Marker({
          map: resultsMap,
          animation: google.maps.Animation.DROP,
          icon: image,
          position: results[0].geometry.location
        });
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }


  function geocodeLatLng(geocoder, map) {
        var latlng = {lat: parseFloat(document.getElementsByClassName('google_map_contact_cls')[0].getAttribute("data-lat")), lng: parseFloat(document.getElementsByClassName('google_map_contact_cls')[0].getAttribute("data-lng"))};
        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[1]) {
                document.getElementById('address').value = results[1].formatted_address;
            } else {
              window.alert('No results found');
            }
          } else {
            window.alert('Geocoder failed due to: ' + status);
          }
        });
      }

function pushNewLatLng(ID, Lat, Lng) {
    var widgets = window.widgets;
    //And write with new props
     widgets.map(function (obj, i) {
        if (ID in obj) {
            widgets.splice(i, 1)
        }
    })
    var w = {};
        w[ID]= {
            'config': null,
            'data': {
                'lat' : Lat,
                'lng' : Lng
            }
        }
    widgets.push(w);
}

initMap($('.google_map_contact_cls').attr('data-lat'), $('.google_map_contact_cls').attr('data-lng'), 16 );

