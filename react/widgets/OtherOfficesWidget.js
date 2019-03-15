import React from 'react'
import ClientOtherOfficesComponent from '../components/client/OtherOffices'
import AdminOtherOfficesComponent from '../components/admin/OtherOffices'

export default class OtherOfficesWidget extends React.Component {
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
            W = <AdminOtherOfficesComponent {...this.state} />;
        }
        else {
            W = <ClientOtherOfficesComponent {...this.state} />;
        }
        return(
            <div>
                {W}
            </div>
        )
    }
}

