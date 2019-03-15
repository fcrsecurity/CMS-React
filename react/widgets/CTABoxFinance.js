import React from 'react'
import ClientCTABoxFinanceComponent from '../components/client/CTABoxFinance'
import AdminCTABoxFinanceComponent from '../components/admin/CTABoxFinance'

export default class CTABoxFinance extends React.Component {
    constructor(props) {
        super(props)
        // console.log(this.props.isLoggedIn)
        if (this.props) {
            this.state = {
                id: this.props.id,
                data: this.props.data,
                isLoggedIn: this.props.isLoggedIn,
                config: this.props.config
            }
        } else {
            this.state = {
                data: null
            }
        }
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;

        let W = null;
        if (isLoggedIn) {
            W = <AdminCTABoxFinanceComponent {...this.state.data} />;
        } else {
            W = <ClientCTABoxFinanceComponent {...this.state.data} />;
        }
        return(
            <div className="widgetWrapperCTABoxFinance">
                {W}
            </div>
        )
    }
}
