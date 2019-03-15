import React from 'react'

const GovernanceTabs = (props) => {

    let arr = Object.values(props.items);

    const navItems = arr.map((item, i) => {
        let clsActive = '';
            if (i == 0) {
                clsActive = 'active';
            } else {
                clsActive = '';
            }
        return (
            <li key={i} role="presentation" className={clsActive}>
                <a href={"#tab"+i} aria-controls={"tab"+i} role="tab" data-toggle="tab">{item.title}</a>
            </li>
        )
    });
    // Start tab loop
    const contentItems = arr.map((item, i) => {
        let iconArr = Object.values(item.content);
        let itemCount = iconArr.length;
        let col = Math.round(12/itemCount);

        // Start icon loop
        const icons = iconArr.map((element, i) => {
            return(
                <div key={i} className={"col-xs-12 col-sm-12 col-md-"+col+" col-lg-"+col+" text-center"}>
                    <div className="thumbnails">
                        <a href={element.url} target="_blank">
                        <span dangerouslySetInnerHTML={{__html: element.icon}}/>
                            <p>
                                {element.text}
                            </p>
                        </a>
                    </div>
                </div>
            )
        });
        //End icon loop


         let clsActive = '';
            if (i == 0) {
                clsActive = 'in active';
            } else {
                clsActive = '';
            }
        return (
            <div key={i} role="tabpanel" className={"tab-pane fade "+clsActive} id={"tab"+i}>
                    {icons}
            </div>
        )
    });
    //End tab loop
    return(
    <div className="sectionWrapper">
        <ul className="nav nav-tabs" role="tablist">
            {navItems}
        </ul>
        <div className="tab-content">
            {contentItems}
        </div>
    </div>  
    )
}

export default GovernanceTabs
