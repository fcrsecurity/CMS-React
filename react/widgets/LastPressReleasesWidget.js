import React from 'react'
import ClientLastPressReleasesComponent from '../components/client/LastPressReleases'
import AdminLastPressReleasesComponent from '../components/admin/LastPressReleases'

export default class LastPressReleasesWidget extends React.Component {
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
            W = <AdminLastPressReleasesComponent {...this.state} />;
        }
        else {
            W = <ClientLastPressReleasesComponent {...this.state} />;
        }
        return(
            <div className={this.state.config.wrapClasses}>
                {W}
            </div>
        )
    }
}

