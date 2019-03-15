import React from 'react'

const TeamTabs = (props) => {

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

    const PropertyF = (property) => {
        if(property.length == 0){
            return(
               <div className="available-text">
                    Sorry, no positions available at present for <br/>
                    Subscribe today to be the first to know about new job postings.
                    <form action="#" id="careers-form">
                        <div className="wrap-subscribe">
                            <div className="message alert"></div>
                            <label for="email-careers-newsletters" className="sr-only">Email For Newsletter</label>
                            <input id="email-careers-newsletters" type="email" name="careers[email]" placeholder="Email" className="input-s"/>
                            <input type="hidden" name="careers[careers]" value="1"/>
                            <button className="subscribe-btn">SUBSCRIBE <em className="fa fa-angle-right" aria-hidden="true"></em></button>
                        </div>
                    </form>     
                </div>
            )
        } else {
            const listItems = property.map((elem,i) =>{
                return (
                    <a key={i} href={elem.href} className="element">
                        <span className="wrap-span">
                            <span className="city">{elem.city}</span>
                            <span className="position">{elem.position}</span>
                            <span className="department">{elem.department}</span>
                        </span>
                    </a>
                )
            });
            return(
                <div className="wrapper-element">   
                      {listItems}
                </div>
            )
        }
    }

    const Content = (str) => {
        if(str.length != 0){
            return(
               <p className="text-center">{str}</p>
            )
        }
    }

    const contentItems = arr.map((item, i) => {
        let clsActive = '';

        if (i == 0) {
            clsActive = 'in active';
        } else {
            clsActive = '';
        }

        return (
            <div key={i} className={"tab-pane fade "+clsActive} id={"tab"+i}>
               {Content(item.content)} 
               {PropertyF(Object.values(item.property))}
            </div>
        )
    });

    return(
       <div className="row">
            <div className="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 tabs">
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

export default TeamTabs