import React from 'react'
import ClientTabsComponent from '../components/client/Tabs'
import AdminTabsComponent from '../components/admin/Tabs'

export default class TabsWidget extends React.Component {
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
            W = <AdminTabsComponent {...this.state} />;
        }
        else {
            W = <ClientTabsComponent {...this.state} />;
        }
        return(
            <div className="widgetWrapperTabs">
                {W}
            </div>
        )
    }
}

