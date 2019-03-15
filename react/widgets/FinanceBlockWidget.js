import React from 'react'
import ClientFinanceBlockComponent from '../components/client/FinanceBlock'
import AdminFinanceBlockComponent from '../components/admin/FinanceBlock'

export default class FinanceBlockWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            let arrow = '';
            let arrowTo = 'up';
            if (parseFloat(this.props.val_usd.replace('$', '')) < 0) {
                arrow = 'pink-arrow';
                arrowTo = 'down';
            }
            this.state = {
                id: this.props.id,
                time: this.props.time,
                index: this.props.index,
                val_usd: this.props.val_usd,
                val_percent: this.props.val_percent,
                arrow: arrow,
                arrowTo: arrowTo,
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
            W = <AdminFinanceBlockComponent {...this.state} />;
        }
        else {
            W = <ClientFinanceBlockComponent {...this.state} />;
        }
        return(
            <div className="widgetWrapperFinanceBlock">
                {W}
            </div>
        )
    }
}

