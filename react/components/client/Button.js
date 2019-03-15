import React from 'react'

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
                <em className="fa fa-angle-right" aria-hidden="true"></em>
            </a>
        </div>
    )
}

export default Button
