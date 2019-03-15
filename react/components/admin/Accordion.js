import React from 'react'

const Accordion = (props) => {
let arr = Object.values(props.items);

    const panelItems = arr.map((item, i) => {
        return (
              <div key={i} className="panel panel-default">
                <div className="panel-heading" data-toggle="collapse" href={"#collapse"+i} data-parent="#accordion">
                  <div className="panel-title">
                   {item.title}
                  </div>
                </div>
                <div  id={"collapse"+i} className="panel-collapse collapse">
                   <div className="panel-body">
                        <div className="row">
                            {item.content}
                        </div>
                    </div>
                </div>
              </div>
        )
    });
    
    return(
        <div className="accordionElement">
            <div className="panel-group" id="accordion">
                {panelItems}
            </div>
        </div>
    )
}

export default Accordion
