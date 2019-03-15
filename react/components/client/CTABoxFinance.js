import React from 'react'

const CTABoxFinance = (props) => {
    let upDown = '';
    if (props.cls != '') {
        upDown = 'down'
    }else {
        upDown = 'up'
    }
    return(
        <div className="cta-box-finance">
            <div className="date">TSX:FCR<span> as of {props.time} EDT</span></div>
            <div className={"wrap-index "+props.cls}>
                <div className="ind">{props.index}</div>
                <div className="clearfix"></div>
                    <div className="arrow ">
                        <em className={"fa fa-angle-"+upDown} aria-hidden="true"></em>
                    </div>
                <div className="usd">
                ({props.val_usd})
                <br/>
                ({props.val_percent})
                </div>
            </div>
        </div>
    )
}

export default CTABoxFinance