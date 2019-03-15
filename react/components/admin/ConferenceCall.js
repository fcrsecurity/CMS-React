import React from 'react'

const ConferenceCall = (props) => {
    return(
        <div className={"conference-call row "+props.config.widgetClasses}>
            <div className="col-lg-4 col-lg-offset-4 text-center text-call-block">
                <h3>{props.title}</h3>
                <p>{props.subtitle}</p>
                <a href={props.href} className="call-btn" target="_blank" dangerouslySetInnerHTML={{__html: props.icon}}></a>
            </div>
        </div>
    )
}

export default ConferenceCall
