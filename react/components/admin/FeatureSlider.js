import React from 'react'

const FeatureSlider = (props) => {
    
    let arr = Object.values(props.items);

    const resultItems = arr.map((item, i) => {
        return (
            <div key={i} className="item">
                <div className="image-container" style={{backgroundImage: 'url(' + item.picture + ')'}}>
                    <h2 className="title-carousel">{item.title}</h2>
                    <div className="overlay"></div>
                </div>
                <a href={item.link}>
                  <div className="descr">
                      <p className="name">{item.name}</p>
                      <p className="city">{item.city}</p>
                  </div>
                </a>
            </div>
        )
    });

    return(
        <div className="feature-slider-js">
            {resultItems}
        </div>
    )
}

export default FeatureSlider
