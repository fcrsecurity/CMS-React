import React from 'react'

const Text = (props) => {
    return(
        <div className={props.config.wrapClasses}>
            <div className={"inline-mode " + props.config.widgetClasses}>
                {props.text}
            </div>
        </div>
    )
}
export default Text
