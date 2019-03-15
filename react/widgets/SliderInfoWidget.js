import React from 'react'
import ClientSliderInfoComponent from '../components/client/SliderInfo'
import AdminSliderInfoComponent from '../components/admin/SliderInfo'

export default class SliderInfoWidget extends React.Component {
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
            W = <AdminSliderInfoComponent {...this.state} />;
        }
        else {
            W = <ClientSliderInfoComponent {...this.state} />;
        }
        return(
            <div className={this.state.config.wrapperClass+" "+this.state.config.widgetClasses}>
                {W}
            </div>
        )
    }
}

