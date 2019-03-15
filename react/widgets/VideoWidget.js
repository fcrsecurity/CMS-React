import React from 'react'
import ReactDOM from 'react-dom'
import ClientVideoComponent from '../components/client/Video'
import AdminVideoComponent from '../components/admin/Video'

export default class VideoWidget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                //Setting config props
                config: this.props.config,
                //Setting data props
                id: this.props.id,
                image: this.props.image,
                icon: this.props.icon,
                videoUrl: this.props.videoUrl,
                isLoggedIn: this.props.isLoggedIn,
            }
        }
        else {
            this.state = null
        }
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;

        let W = null;
        if (isLoggedIn) {
            W = <AdminVideoComponent {...this.state} />;
        }
        else {
            W = <ClientVideoComponent {...this.state} />;
        }
        return(
            <div className={this.props.config.wrapClasses}>
                {W}
            </div>
        )
    }
}

