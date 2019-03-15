import React from 'react'
import ClientButtonComponent from '../components/client/Button'
import AdminButtonComponent from '../components/admin/Button'

export default class ButtonWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            this.state = {
                id: this.props.id,
                wrapClasses: this.props.wrapClasses,
                widgetClasses: this.props.widgetClasses,
                text: this.props.text,
                isLoggedIn: this.props.isLoggedIn,
                href: this.props.href,
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
            W = <AdminButtonComponent {...this.state} />;
        }
        else {
            W = <ClientButtonComponent {...this.state} />;
        }
        return(
            <div>
                {W}
            </div>
        )
    }
}

