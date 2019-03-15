import React from 'react'
import ClientTitleComponent from '../components/client/Title'
import AdminTitleComponent from '../components/admin/Title'
import {setWidgetData} from '../utilities/setWidget'

export default class TitleWidget extends React.Component {
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

    _handleChange (ev, value, el) {
        this.setState({[el]: value}, this.setWidget)
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;

        let W = null;
        if (isLoggedIn) {
            W = <AdminTitleComponent {...this.state} _handleChange={this._handleChange} />;
        }
        else {
            W = <ClientTitleComponent {...this.state} />;
        }
        return(
            <div>
                {W}
            </div>
        )
    }
}

