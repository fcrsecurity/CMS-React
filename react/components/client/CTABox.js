import React from 'react'

const CTABox = (props) => {
    return(
    	<div className="cta-box-item" style={{backgroundImage: 'url(' + props.image + ')'}}>
		    <div className="text">
		        <span>{props.title}</span>
		        <p><strong>{props.description}</strong></p>
		    </div>
		    <a className="more" href={props.link}>LEARN MORE</a>
		    <em className="fa fa-angle-down arrow-style" aria-hidden="true"></em>
		</div>
    )
}

export default CTABox