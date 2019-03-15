import React from 'react'

const Title = (props) => {
    const createHTag  = (el) => {
        return {__html: '<'+el+' class="inline-mode ' + props.config.widgetClasses+'">'
                        + props.text
                        + '</'+el+'>'}
    }

    return(
        <div className={props.config.wrapClasses}>
            <div dangerouslySetInnerHTML={createHTag(props.config.htag)}/>
        </div>
    )
}

export default Title
