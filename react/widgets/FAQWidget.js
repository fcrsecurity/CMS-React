import React from 'react'
import ClientFAQComponent from '../components/client/FAQ'
import AdminFAQComponent from '../components/admin/FAQ'

export default class FAQWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                id: this.props.id,
                items: this.props.items,
                config: this.props.config,
                isLoggedIn: this.props.isLoggedIn
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
            W = <AdminFAQComponent {...this.state} />;
        }
        else {
            W = <ClientFAQComponent {...this.state} />;
        }
        return(
            <div className={this.state.config.wrapClasses}>
                {W}
            </div>
        )
    }
}

