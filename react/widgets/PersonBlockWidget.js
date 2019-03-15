import React from 'react'
import ClientPersonBlockComponent from '../components/client/PersonBlock'
import AdminPersonBlockComponent from '../components/admin/PersonBlock'

export default class PersonBlockWidget extends React.Component {
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
            W = <AdminPersonBlockComponent {...this.state} />;
        }
        else {
            W = <ClientPersonBlockComponent {...this.state} />;
        }
        return(
            <div className={"person-block "+this.props.config.wrapperClass}>
                {W}
            </div>
        )
    }
}

