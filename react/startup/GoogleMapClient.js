import React from 'react'
import GoogleMapWidget from '../widgets/GoogleMapWidget'

export default (initialProps) => {
    return (
        <div>
            <GoogleMapWidget {...initialProps} />
        </div>
    )
}
