import React from 'react'
import ClientAnalystCoverageComponent from '../components/client/AnalystCoverage'
import AdminAnalystCoverageComponent from '../components/admin/AnalystCoverage'

export default class AnalystCoverageWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                title: this.props.title,
                subtitle: this.props.subtitle,
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
            W = <AdminAnalystCoverageComponent {...this.state} />;
        }
        else {
            W = <ClientAnalystCoverageComponent {...this.state} />;
        }
        return(
            <div className="widgetWrapper {this.props.styleName}">
                {W}
            </div>
        )
    }
}

