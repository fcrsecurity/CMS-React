import React from 'react'

const RichEditor = (props) => {
    const createContent  = () => {
        return {__html: props.content}
    }

    return(
        <div dangerouslySetInnerHTML={createContent()}/>
    )
}

export default RichEditor