import React from 'react'
import ClientPressReleasesComponent from '../components/client/PressReleases'
import AdminPressReleasesComponent from '../components/admin/PressReleases'

export default class PressReleasesWidget extends React.Component {
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
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;

        let W = null;
        if (isLoggedIn) {
            W = <AdminPressReleasesComponent {...this.state} />;
        }
        else {
            W = <ClientPressReleasesComponent {...this.state} />;
        }
        return(
            <div className={this.state.config.widgetClasses}>
                {W}
            </div>
        )
    }
}

