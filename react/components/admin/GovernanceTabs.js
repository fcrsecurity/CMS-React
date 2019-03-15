import React from 'react'
import ContentEditable from '../ContentEditable'

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
        let idx = i+1;
        let gparent = 'item' + idx;
        let iconArr = Object.values(item.content);
        let itemCount = iconArr.length;
        let col = Math.round(12/itemCount);

        // Start icon loop
        const icons = iconArr.map((element, i) => {
            let idx = i+1;
            return(
                <div key={i} className={"col-xs-12 col-sm-12 col-md-"+col+" col-lg-"+col+" text-center"}>
                    <div className="thumbnails">
                        <a href={element.url} target="_blank">
                        <span dangerouslySetInnerHTML={{__html: element.icon}}/>
                        </a>
                        <ContentEditable
                            el="text"
                            parent={"item"+idx}
                            gparent={gparent}
                            html={element.text}
                            className="inline-mode"
                            onChange={ props._handleChange }
                            contentEditable="plaintext-only"
                        />
                        <ContentEditable
                            el="url"
                            parent={"item"+idx}
                            gparent={gparent}
                            html={element.url}
                            style={{ display: 'none'}}
                            className="inline-mode cfg-tool"
                            onChange={ props._handleChange }
                            contentEditable="plaintext-only"
                        />
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
