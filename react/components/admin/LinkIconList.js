import React from 'react'
import ContentEditable from '../ContentEditable'

const LinkIconList = (props) => {

    let arr = Object.values(props.items),
        itemCount = arr.length,
        col = Math.round(12/itemCount);

    // Loop to collect items in LinkIconList Items variable
    const items = arr.map((item, i) => {
        /*const createMarkup = (str) => {
            return {__html: str};
        }
        item.icon = createMarkup(item.icon)*/
        let idx = i+1;
        return (
            <div key={i} className={"col-xs-12 col-sm-12 col-md-"+col+" col-lg-"+col+" text-center"}>
                <div className="thumbnails">
                    <a href={item.url} target="_blank">
                        <span dangerouslySetInnerHTML={{__html: item.icon}}/>
                    </a>
                    <button className="btn btn-primary inline-mode cfg-tool open-elfinder"
                       id={props.id.toString() + i}
                       style={{ display: 'none'}}>
                        File
                    </button>
                    <ContentEditable
                        el="text"
                        parent={"item"+idx}
                        html={item.text}
                        className="inline-mode"
                        onChange={ props._handleChange }
                        contentEditable="plaintext-only"
                    />
                    <ContentEditable
                        el="url"
                        parent={"item"+idx}
                        html={item.url}
                        style={{ display: 'none'}}
                        id={"linkFilePath" + props.id + i}
                        className="inline-mode cfg-tool"
                        onChange={ props._handleChange }
                        contentEditable="plaintext-only"
                    />
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
