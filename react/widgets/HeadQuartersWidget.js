import React from 'react'
import ClientHeadQuartersComponent from '../components/client/HeadQuarters'
import AdminHeadQuartersComponent from '../components/admin/HeadQuarters'
import {setWidgetData} from '../utilities/setWidget'

export default class HeadQuartersWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                id: this.props.id,
                title: this.props.title,
                addres1: this.props.addres1,
                addres2: this.props.addres2,
                tel: this.props.tel,
                tollFree: this.props.tollFree,
                fax: this.props.fax,
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

    setWidget() {
        let state = this.state;
        let conf = this.state.config;
        setWidgetData(state, conf)
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;

        let W = null;
        if (isLoggedIn) {
            W = <AdminHeadQuartersComponent {...this.state} />;
        }
        else {
            W = <ClientHeadQuartersComponent {...this.state} />;
        }
        return(
            <div>
                {W}
            </div>
        )
    }
}

