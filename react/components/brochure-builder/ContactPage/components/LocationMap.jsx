// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

import { BrochureBuilder } from '../../BrochureBuilder';

// @ts-ignore
import pin from '../assets/pin.png';
// @ts-ignore
import north from '../assets/north-symbol-x100.png';

// @ts-ignore
import styles from '../ContactPage.scss';

/**
 * @augments {React.Component<{
		latitude: number;
		longitude: number;
		zoom?: number;
	}, {
		latitude: number;
		longitude: number;	
	}>}
 */
export class LocationMap extends React.Component {

	ref = null;
	google = BrochureBuilder.google;

	map = null;
	marker = null;
	listener = null;

	static propTypes = {
		zoom: PropTypes.number,
		latitude: PropTypes.number.isRequired,
		longitude: PropTypes.number.isRequired
	};

	static defaultProps = {
		zoom: 17
	};

	state = {
		latitude: this.props.latitude,
		longitude: this.props.longitude
	};

	createOptions(lat, lng, zoom) {
		return {
			// How zoomed in you want the map to start at (always required)
			zoom: zoom,
			draggable: true,
			scrollwheel: false,
			zoomControl: true,
			zoomControlOptions: {
				position: this.google.maps.ControlPosition.RIGHT_CENTER
			},
			minZoom: 15,
			maxZoom: 18,
			mapTypeControl: false,
			scaleControl: false,
			fullscreenControl: false,
			streetViewControl: false,
		
			// The latitude and longitude to center the map (always required)
			center: new this.google.maps.LatLng(lat, lng),
		
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
	}

	componentDidMount() {
		if (this.ref) {
			const { zoom } = this.props;
			const { latitude, longitude } = this.state;

			const options = this.createOptions(latitude, longitude, zoom);

			// Create the Google Map using our element and options defined above
			this.map = new this.google.maps.Map(this.ref, options);

			// Marker Image
			this.marker= new this.google.maps.Marker({
				map: this.map,
				position: options.center,
				animation: this.google.maps.Animation.DROP,
				icon: {
					url: pin,
					scaledSize: new this.google.maps.Size(22, 34)
				}
			});

			this.listener = this.google.maps.event.addListener(this.map, 'click', (event) => {
				this.setState({
					latitude: event.latLng.lat(),
					longitude: event.latLng.lng()
				});
			});
		}
	}

	componentWillUnmount() {
		this.listener.remove();
	}

	changeLocation({ latitude, longitude }) {
		const latLng = new this.google.maps.LatLng(latitude, longitude);

		this.map.panTo(latLng);
		this.marker.setPosition(latLng);

		if (this.marker.getAnimation() !== null) {
			this.marker.setAnimation(null);
		}

		this.marker.setAnimation(this.google.maps.Animation.BOUNCE);
		window.setTimeout(() => this.marker.setAnimation(null), 500);
	}

	shouldComponentUpdate(nextProps, { latitude, longitude }) {
		if (this.state.latitude !== latitude || this.state.longitude !== longitude) {
			this.changeLocation({ latitude, longitude });
		}

		return false;
	}

	getLocationData() {
		const { latitude, longitude } = this.state;
		return {
			latitude, longitude
		};
	}

	getZoomData() {
		return this.map.getZoom();
	}

	render() {
		return (
			<div className={ styles.mapwrp }>
				<div className={ styles.mapwrp } ref={ (ref) => this.ref = ref } />
				<img className={ styles.north } src={ north } alt="North Symbol" />
			</div>
		);
	}
}
