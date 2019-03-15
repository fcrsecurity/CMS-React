import React from 'react'

const GoogleMap = (props) => {
    return(
        <div id={"google-map-widget-"+props.id}
             className="row gmap-widget"
             data-auth={props.isLoggedIn}
             data-lat={props.lat}
             data-lng={props.lng} >
        </div>
    )
}

export default GoogleMap
