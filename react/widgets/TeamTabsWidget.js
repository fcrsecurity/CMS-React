import React from 'react'
import ClientTeamTabsComponent from '../components/client/TeamTabs'
import AdminTeamTabsComponent from '../components/admin/TeamTabs'

export default class TeamTabsWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
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
            W = <AdminTeamTabsComponent {...this.state} />;
        }
        else {
            W = <ClientTeamTabsComponent {...this.state} />;
        }
        return(
            <div className={this.props.config.wrapClasses+" "+this.props.config.widgetClasses}>
                {W}
            </div>
        )
    }
}

