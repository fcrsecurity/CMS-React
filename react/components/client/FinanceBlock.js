import React from 'react'

const FinanceBlock = (props) => {
    return(
        <div className="finance-block">
            <div className="ind">
                <div className="first_row_ind">
                    <div className="ind_value"><em>{props.index}</em></div>
                    <div className={"positive-results "+props.arrow}>
                        <strong className={"statArrow fa fa-angle-"+props.arrowTo} aria-hidden="true"></strong>
                        <div className="usd">({props.val_usd})<br/>({props.val_percent})</div>
                    </div>
                </div>
                <span className="date">{props.time}</span>
            </div>
        </div>
    )
}

export default FinanceBlock
