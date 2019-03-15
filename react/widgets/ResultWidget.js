import React from 'react'
import update from 'react-addons-update'
import ClientResultComponent from '../components/client/Result'
import AdminResultComponent from '../components/admin/Result'
import {setWidgetData} from '../utilities/setWidget'

export default class ResultWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            this.state = {
                //Setting data props
                id: this.props.id,
                title: this.props.title,
				subtitle: this.props.subtitle,
				items: this.props.items,
				isLoggedIn: this.props.isLoggedIn,
                //Setting config props
                config: this.props.config
            }
        }
		else {
            this.state = {
                data: null
            }
        }
    this._handleChange = this._handleChange.bind(this);
    this._addItem = this._addItem.bind(this);
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

    _addItem () {
        let id = 'item' + 1;
        let item = {
            [id]: {title:'', subtitle: ''}
        };
        this.setState({
            items: update(this.state.items, {$merge: item})
        },this.setWidget)
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;

        let W = null;
        if (isLoggedIn) {
            W = <AdminResultComponent {...this.state} _handleChange={this._handleChange} _addItem={this._addItem} />;
        }
		else {
            W = <ClientResultComponent {...this.state} />;
        }
        return(
            <div>
                {W}
            </div>
        )
    }
}
