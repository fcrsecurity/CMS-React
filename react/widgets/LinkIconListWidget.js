import React from 'react'
import update from 'react-addons-update'
import ClientLinkIconListComponent from '../components/client/LinkIconList'
import AdminLinkIconListComponent from '../components/admin/LinkIconList'
import {setWidgetData} from '../utilities/setWidget'

export default class LinkIconListWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            this.state = {
                //Setting data props
                id: this.props.id,
                items: this.props.items,
                //Setting config props
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

    _handleChange (ev, value, el, parent) {
        /* If widget contains items */
        if(parent) {
            this.setState({
                items: update(this.state.items, {[parent]: {[el]: {$set: value}}})
            },this.setWidget)
        } else {
            /* For main widget props */
            this.setState({[el]: value}, this.setWidget)
        }
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;
        let W = null;
        if (isLoggedIn) {
            W = <AdminLinkIconListComponent {...this.state} _handleChange={this._handleChange} />;
        }
        else {
            W = <ClientLinkIconListComponent {...this.state} />;
        }
        return(
            <div className={this.state.config.widgetClasses+" "+this.state.config.wrapClasses}>
                {W}
                <div id="linkIconListElfinder"></div>
            </div>
        )
    }
}

