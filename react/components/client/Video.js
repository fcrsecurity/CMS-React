import React from 'react'

const Video = (props) => {
    const createSVG = (str) => {
        return {__html: str};
    }
    return(
        <div>
            <div className={props.config.widgetClasses}
                 style={{background: 'url(' + props.image + ') no-repeat center center'}}>
                <a className={'widget-play-video playVideo'+props.id+' '+props.config.iconClasses} href="#retail-arts">
                    <div dangerouslySetInnerHTML={{__html: props.icon}}/>
                </a>
            </div>
            <div className={"widget-video video"+props.id} style={{display: 'none'}}>
                <iframe id={props.id} className="video-widget" src={props.videoUrl}
                        frameBorder="0" allowFullScreen></iframe>
                <a href="#" className="closeVideo">{props.config.buttonText}</a>
            </div>
        </div>
    )
}

export default Video