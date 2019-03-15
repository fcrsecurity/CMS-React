import React from 'react'
import ReactDOM from 'react-dom'

export default class GoogleMap extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            lat: Number(this.props.lat),
            lng: Number(this.props.lng),
            center: {
                lat: Number(this.props.lat),
                lng: Number(this.props.lng),
            },
            isLoggedIn: this.props.auth,
        }
    }

    componentDidMount() {
        // alert(JSON.stringify(this.state));
        let mapOptions = {
            zoom: 13,
            draggable: true,
            scrollwheel: false,
            center: this.state.center,
            styles: [{"featureType":"all","elementType":"all","stylers":[{"hue":"#e7ecf0"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-70}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"all","stylers":[{"visibility":"on"},{"color":"#449cd5"}]},{"featureType":"transit.station","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit.station.rail","elementType":"all","stylers":[{"visibility":"on"},{"hue":"#009cff"}]},{"featureType":"transit.station.rail","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#00fff5"}]},{"featureType":"transit.station.rail","elementType":"geometry.stroke","stylers":[{"visibility":"simplified"},{"hue":"#00ffcd"}]},{"featureType":"transit.station.rail","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"color":"#ffffff"}]},{"featureType":"transit.station.rail","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#626264"}]},{"featureType":"transit.station.rail","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60}]}]
        };
        this.map = new google.maps.Map(this.refs.map, mapOptions);
        let map_marker = new google.maps.MarkerImage('https://fcr.ca/uploads/icons/map_pin@2x.png');
        var marker = new google.maps.Marker({
            position: this.state.center,
            icon: map_marker,
            map: this.map,
            animation: google.maps.Animation.DROP,
            title: 'FCR'
        });
    }

    render() {
        const mapStyle = {
            width: '100%',
            height: '450px',
        };

        return (
            <div ref="map" style={mapStyle}></div>
        );
    }
}

if(typeof window !== 'undefined') {
    let maps = document.getElementsByClassName("gmap-widget");
    if (maps.length > 0) {
    Array.from(maps).forEach(function(map) {
        let id = map.getAttribute("id"),
            lat = map.getAttribute('data-lat'),
            lng = map.getAttribute('data-lng'),
            auth = map.getAttribute('data-auth');
            ReactDOM.render(<GoogleMap
                lat={lat}
                lng={lng}
                auth={auth} />,
            document.getElementById(id));
        });
    }
}

