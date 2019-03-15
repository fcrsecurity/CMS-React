import React from 'react'
import ClientAccordionComponent from '../components/client/Accordion'
import AdminAccordionComponent from '../components/admin/Accordion'

export default class AccordionWidget extends React.Component {
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
            W = <AdminAccordionComponent {...this.state} />;
        }
        else {
            W = <ClientAccordionComponent {...this.state} />;
        }
        return(
            <div className={this.state.config.widgetClasses}>
                {W}
            </div>
        )
    }
}

