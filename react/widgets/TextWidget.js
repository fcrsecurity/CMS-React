import React from 'react'
import ClientTextComponent from '../components/client/Text'
import AdminTextComponent from '../components/admin/Text'
import {setWidgetData} from '../utilities/setWidget'

export default class TextWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            this.state = {
                id: this.props.id,
                text: this.props.text,
                config: this.props.config,
                isLoggedIn: this.props.isLoggedIn,
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
            W = <AdminTextComponent {...this.state} _handleChange={this._handleChange} />;
        }
        else {
            W = <ClientTextComponent {...this.state} />;
        }
        return(
            <div>
                {W}
            </div>
        )
    }
}

