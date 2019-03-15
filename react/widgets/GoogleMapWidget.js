import React from 'react'
import ClientGoogleMapComponent from '../components/client/GoogleMap'
import AdminGoogleMapComponent from '../components/admin/GoogleMap'

export default class GoogleMapWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                id: this.props.id,
                lat: this.props.lat,
                lng: this.props.lng,
                isLoggedIn: this.props.isLoggedIn,
                config: this.props.config
            }
        }
        else {
            this.state = {
                data: null
            }
        }
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;
        let W = null;
        if (isLoggedIn) {
            W = <AdminGoogleMapComponent {...this.state} />;
        }
        else {
            W = <ClientGoogleMapComponent {...this.state} />;
        }
        return(
            <div className="widgetWrapper">
                {W}
            </div>
        )
    }
}

