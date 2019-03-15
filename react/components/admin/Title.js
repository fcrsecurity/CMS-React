import React from 'react'
import ContentEditable from '../ContentEditable'

const Title = (props) => {
    return(
        <div className={props.config.wrapClasses}>
            <ContentEditable
                el="text"
                html={props.text}
                className={"inline-mode " + props.config.widgetClasses}
                onChange={ props._handleChange }
                contentEditable="plaintext-only"
            />
        </div>
    )
}

export default Title
