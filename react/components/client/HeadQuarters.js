import React from 'react'

const HeadQuarters = (props) => {

    return(
        <div className="col-xs-12 col-xs-offset-3 col-sm-12 col-sm-offset-4 col-md-12 col-md-offset-5 col-lg-7 col-lg-offset-5">
            <p>
                <strong>{props.title}</strong><br />
                {props.addres1} <br />
                {props.addres2}
            </p>
            <p>
                Tel: <a href={"tel:"+props.tel}>{props.tel}</a><br />
                Toll Free:<a href={props.tollFree}>{props.tollFree}</a><br />
                Fax: {props.fax}<br />
            </p>
        </div>
    )
}

export default HeadQuarters
