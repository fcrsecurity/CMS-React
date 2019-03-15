import React from 'react'

const OtherOffices = (props) => {

    let arr = Object.values(props.items),
        itemCount = arr.length,
        col = Math.round(12/itemCount);

    const items = arr.map((item, i) => {
        return (
                <div key={i} className={"col-xs-"+col+" col-sm-"+col+" col-md-"+col+" col-lg-"+col+" "}>
                    <p>
                        <strong>{item.title}</strong><br />
                        {item.subTitle} <br />
                        {item.place} <br />
                        {item.addres1} <br />
                        {item.addres2}
                    </p>
                    <p>
                        Tel: <a href={"tel:"+item.tel}>{item.tel}</a><br />
                        Fax: <a href={item.fax}>{item.fax}</a><br />
                    </p>
                </div>
                )
    });

    return(
         <div className="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
            {items}
        </div>
    )
}

export default OtherOffices
