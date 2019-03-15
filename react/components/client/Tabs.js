import React from 'react'

const Tabs = (props) => {

    let arr = Object.values(props.items);

    const navItems = arr.map((item, i) => {
        let clsActive = '';
            if (i == 0) {
                clsActive = 'active';
            } else {
                clsActive = '';
            }
        return (
            <li key={i} className={clsActive}>
                <a href={"#tab"+i} aria-controls={"tab"+i} role="tab" data-toggle="tab">{item.title}</a>
            </li>
        )
    });
    const contentItems = arr.map((item, i) => {
         let clsActive = '';
            if (i == 0) {
                clsActive = 'in active';
            } else {
                clsActive = '';
            }
        return (
            <div key={i} className={"tab-pane fade "+clsActive} id={"tab"+i}>
               {item.content}
            </div>
        )
    });

    return(
       <div className="row">
            <div className="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 tabs">
                <ul className="nav nav-tabs" role="tablist">
                    {navItems}
                </ul>
                <div className="tab-content">
                    {contentItems}
                </div>
            </div>
        </div>
    )
}

export default Tabs