import React from 'react'

const AnalystCoverage = (props) => {
let arr = Object.values(props.items);

    const panelItems = arr.map((item, i) => {

        let arrContent = Object.values(item.content);

        const contentItems = arrContent.map((itemContent, i) => {
            return (
                <div key={i} className="col-lg-3 buffer-7-top">
                <p><strong>{itemContent.title}</strong><br />
                    <small>{itemContent.person}<br />
                    <a href="tel:{itemContent.phone}">{itemContent.phone}</a></small>
                </p>
            </div>
            )
        });
        
        return (
              <div key={i} className="panel panel-default">
                <div className="panel-heading" data-toggle="collapse" href={"#collapse"+i} data-parent="#accordion">
                  <h4 className="panel-title">
                    <a role="button" className="collapsed accordion-toggle">
                        {item.title}
                    </a>
                   
                  </h4>
                </div>
                <div  id={"collapse"+i} className="panel-collapse collapse">
                    <div className="container-fluid">
                       <div className="panel-body">
                            <div className="row">
                                {contentItems}
                            </div>
                        </div>
                    </div>
                </div>
              </div>
        )
    });
    
    return(
        <div className={"accordionElement "+props.config.wrapClasses+""}>
            <div className="panel-group" id="accordion">
                {panelItems}
            </div>
        </div>
    )
}

export default AnalystCoverage
