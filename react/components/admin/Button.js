import React from 'react'
import ContentEditable from '../ContentEditable'

const Button = (props) => {
	var fileUrl = props.href,
    parts, ext = ( parts = fileUrl.split("/").pop().split(".") ).length > 1 ? parts.pop() : "";
    var target = '_self';
    if (ext == 'pdf') {
		target ="_blank";
    }
    // console.log(target);
    return(
        <div className={props.wrapClasses}>
            <a className={props.widgetClasses} href={props.href} target={target}>
                {props.text}
                <i className="fa fa-angle-right" aria-hidden="true"></i>
            </a>
        </div>
    )
}

export default Button
