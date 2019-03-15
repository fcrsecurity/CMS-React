import React from 'react'

const LinkIconList = (props) => {

    let arr = Object.values(props.items);
    let itemCount = arr.length;
    let col = Math.round(12/itemCount);

    // Loop to collect items in LinkIconList Items variable
    const items = arr.map((item, i) => {
        return (
            <div key={i} className={"col-xs-12 col-sm-12 col-md-"+col+" col-lg-"+col+" text-center"}>
                <div className="thumbnails">
                    <a href={item.url} target="_blank">
                        <span dangerouslySetInnerHTML={{__html: item.icon}}/>
                        <p>
                            {item.text}
                        </p>
                    </a>
                </div>
            </div>
        )
    });

    return(
        <div className={"row slick-"+itemCount+"-item-line-arrow position-ir"}>
            {items}
        </div>
    )
}

export default LinkIconList
