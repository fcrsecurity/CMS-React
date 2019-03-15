import React from 'react'
import ClientConferenceCallComponent from '../components/client/ConferenceCall'
import AdminConferenceCallComponent from '../components/admin/ConferenceCall'

export default class ConferenceCallWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                id: this.props.id,
                title: this.props.title,
                subtitle: this.props.subtitle,
                icon: this.props.icon,
                href: this.props.href,
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
            W = <AdminConferenceCallComponent {...this.state} />;
        }
        else {
            W = <ClientConferenceCallComponent {...this.state} />;
        }
        return(
            <div className={this.state.config.wrapClasses}>
                {W}
            </div>
        )
    }
}

