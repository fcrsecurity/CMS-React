import React from 'react'
import ClientPersonBanerComponent from '../components/client/PersonBaner'
import AdminPersonBanerComponent from '../components/admin/PersonBaner'
import {setWidgetData} from '../utilities/setWidget'

export default class PersonBanerWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                id: this.props.id,
                isLoggedIn: this.props.isLoggedIn,
                config: this.props.config,
                photo: this.props.photo,
                name: this.props.name,
                description: this.props.description,
                text: this.props.text,
            }
        }
        else {
            this.state = {
                data: null
            }
        }
        this._handleChange = this._handleChange.bind(this);
    }

    setWidget() {
        let state = this.state;
        let conf = this.state.config;
        setWidgetData(state, conf)
    }

    _handleChange (ev, value) {
        this.setState({text: value}, this.setWidget)
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;

        let W = null;
        if (isLoggedIn) {
            W = <AdminPersonBanerComponent {...this.state} _handleChange={this._handleChange} />;
        }
        else {
            W = <ClientPersonBanerComponent {...this.state} />;
        }
        return(
            <div className="widgetWrapperPersonBaner">
                {W}
            </div>
        )
    }
}

