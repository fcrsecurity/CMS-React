import React from 'react'

const FAQ = (props) => {

    // Example from result widget

    var arr = Object.values(props.items);

    // Loop to collect items in resultItems variable
    const resultItems = arr.map((item, i) => {
        let url;
            if (item.url) {
              url = (
                <a href={item.url} >click here</a>
              )
            }
        return (
            <div key={i} className="panel panel-default">
                <div className="panel-heading" >
                    <h4 className="panel-title"> 
                        <a className="accordion-toggle collapsed"> 
                            {item.title}
                        </a> 
                    </h4>
                </div>
                <div  className="panel-collapse collapse" >
                    <div className="panel-body">
                        {item.text}
                        {url}.
                    </div>
                </div>
            </div>
        )
    });

    return(
        <div className="faq">
            <div className="page-content career-accordion user-accordion" >
            <div id="collapse-init" className="pull-right expand-all " >EXPAND ALL +</div>
            <div className="clearfix"></div>
            
                <div className="clearPanel">
                    <div className="accordionElement">
                        <div className="panel-group " role="tablist" aria-multiselectable="true">
                            {resultItems}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default FAQ
