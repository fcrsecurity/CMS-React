import React from 'react'

const PressReleases = (props) => {
    let arr = Object.values(props.items);
    
    
    const panelItems = arr.map((item, i) => {
        const Elements = item.elements.map((elem, i) => {
          return (
            <div key={i} className="element">
                <div className="col-lg-2 col-md-2 col-sm-3 date">
                  <a href={elem.href}>{elem.date}</a>
                </div>
                <div className="col-lg-9 col-md-9 col-sm-8">
                    <a href={elem.href}>{elem.name}</a>
                </div>
                <div className="col-lg-1 col-md-1 col-sm-1">
                  <a href={elem.file} target="_blank" className="download-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 211 211" className="pdf-icon-svg">
                      <g id="Layer_2" data-name="Layer 2">
                        <g id="nav">
                          <path className="cls-2" d="M62 41.6v114.62h87.7V71.17L120.1 41.6h-58.1z"></path>
                          <path className="cls-3" d="M149.7 71.17L120.1 41.6v29.57h29.57z"></path>
                          <text className="cls-4" transform="translate(85.9 112.31)">PDF</text>
                        </g>
                      </g>
                    </svg>
                  </a>
                </div>
            </div>
          )
        });
        return (
              <div key={i} className="panel panel-default">
                <div className="panel-heading" data-toggle="collapse" href={"#pressreleases"+i} data-parent="#pressreleases">
                  <div className="panel-title">
                   {item.year}
                  </div>
                </div>
                <div  id={"pressreleases"+i} className="panel-collapse collapse">
                   <div className="panel-body">
                        <div className="row">
                          {Elements}
                        </div>
                    </div>
                </div>
              </div>
        )
    });
    
    return(
        <div className="accordionElement PreasReleases">
            <div className="panel-group" id="pressreleases">
                {panelItems}
            </div>
        </div>
    )
}
export default PressReleases
