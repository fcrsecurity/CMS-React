import React from 'react'
import ClientFeatureSliderComponent from '../components/client/FeatureSlider'
import AdminFeatureSliderComponent from '../components/admin/FeatureSlider'

export default class FeatureSliderWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                id: this.props.id,
                items: this.props.items,
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
            W = <AdminFeatureSliderComponent {...this.state} />;
        }
        else {
            W = <ClientFeatureSliderComponent {...this.state} />;
        }
        return(
            <div className="row feature-slider">
                {W}
            </div>
        )
    }
}

