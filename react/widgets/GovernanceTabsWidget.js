import React from 'react'
import update from 'react-addons-update'
import ClientGovernanceTabsComponent from '../components/client/GovernanceTabs'
import AdminGovernanceTabsComponent from '../components/admin/GovernanceTabs'
import {setWidgetData} from '../utilities/setWidget'

export default class GovernanceTabsWidget extends React.Component {
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
    this._handleChange = this._handleChange.bind(this);
    }

    setWidget() {
        let state = this.state;
        let conf = this.state.config;
        setWidgetData(state, conf)
    }

    _handleChange (ev, value, el, parent, gparent) {
        /* If widget contains items */
        if(parent) {
            this.setState({
                items: update(this.state.items, { [gparent] : {content : { [parent] : {[el]: {$set: value}}}}})
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
            W = <AdminGovernanceTabsComponent {...this.state} _handleChange={this._handleChange} />;
        }
        else {
            W = <ClientGovernanceTabsComponent {...this.state} />;
        }
        return(
            <div className="widgetWrapper {this.props.styleName}">
                {W}
            </div>
        )
    }
}

