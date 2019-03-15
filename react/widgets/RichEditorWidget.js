import React from 'react'
import ClientRichEditorComponent from '../components/client/RichEditor'
import AdminRichEditorComponent from '../components/admin/RichEditor'
import {setWidgetData} from '../utilities/setWidget'

export default class RichEditorWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                id: this.props.id,
                content: this.props.content,
                isLoggedIn: this.props.isLoggedIn,
                canEdit: this.props.canEdit,
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

    _handleChange (content) {
        this.setState({content: content}, this.setWidget)
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;
        const canEdit = this.state.canEdit;

        let W = null;
        if (isLoggedIn && canEdit) {
            W = <AdminRichEditorComponent {...this.state} onChange={ this._handleChange } />;
        }
        else {
            W = <ClientRichEditorComponent {...this.state} />;
        }
        return(
            <div className="">
                {W}
            </div>
        )
    }
}

