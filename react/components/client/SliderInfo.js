import React from 'react'

const SliderInfo = (props) => {

    let arr = Object.values(props.items);

    const resultItems = arr.map((item, i) => {
        return (
            <div key={i} className="item" style={{backgroundImage: 'url(' + item.picture + ')'}}>
                <div className="overlay">
                    <div className="text-container">
                        <div className="left">
                            <p>{item.leftText}</p>
                        </div>
                        <div className="right">
                            <div className="title">
                                <p>{item.title}</p>
                            </div>
                            <div className="description">
                                <p>{item.description}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    });

    return(
        <div className="slider-info-js slider-info">
            {resultItems}
        </div>
    )
}

export default SliderInfo
