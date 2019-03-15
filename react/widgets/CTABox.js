import React from 'react'
import update from 'react-addons-update'
import ClientCTABoxComponent from '../components/client/CTABox'
import AdminCTABoxComponent from '../components/admin/CTABox'
import {setWidgetData} from '../utilities/setWidget'

export default class CTABox extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            this.state = {
                id: this.props.id,
                title: this.props.title,
                description: this.props.description,
                image: this.props.image,
                link: this.props.link,
                isLoggedIn: this.props.isLoggedIn,
                config: this.props.config
            }
        } else {
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
        this.setState({link: value}, this.setWidget)
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;

        let W = null;
        if (isLoggedIn) {
            W = <AdminCTABoxComponent {...this.state} _handleChange={this._handleChange} />;
        } else {
            W = <ClientCTABoxComponent {...this.state} />;
        }
        return(
            <div>
                {W}
            </div>
        )
    }
}
