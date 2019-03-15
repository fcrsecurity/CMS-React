import React from 'react'
import ContentEditable from '../ContentEditable'

const PersonBaner = (props) => {
    return(
        <div className="person-baner row">
            <div className="col-lg-6 col-md-6 col-sm-6 seo-image-block">
                <img src={props.photo}/>
            </div>
            <div className="col-lg-6 col-md-6 col-sm-6 blue-block">
                <center>
                    <ContentEditable
                        el="text"
                        html={props.text}
                        className="inline-mode blockquote"
                        onChange={ props._handleChange }
                        contentEditable="plaintext-only"
                    />
                    <div className="signature">
                        <span><strong>{props.name}</strong></span><br />
                        <span>{props.description}</span>
                        <br />
                    </div>
                </center>
            </div>
        </div>  
    )
}

export default PersonBaner
