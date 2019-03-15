import React from 'react'
import ContentEditable from '../ContentEditable'

const CTABox = (props) => {
    return(
        <div className="cta-box-item" style={{backgroundImage: 'url(' + props.image + ')'}}>
		    <div className="text">
		        <span>{props.title}</span>
		        <p><strong>{props.description}</strong></p>
		    </div>
		    <a className="more" href={props.link}>LEARN MORE</a>
		    <i className="fa fa-angle-down arrow-style" aria-hidden="true"></i>
		    <ContentEditable
                el="link"
                html={props.link}
                className="inline-mode link-cta"
                onChange={ props._handleChange }
                contentEditable="plaintext-only"
            />
		</div>
    )
}

export default CTABox
