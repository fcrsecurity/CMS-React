import React from 'react'

const PersonBlock = (props) => {

  /*  function makeid()
    {
        let text = "";
        let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for( let i=0; i < 5; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }*/

    let arr = Object.values(props.items);
    let itemCount = arr.length;
    let col = Math.round(12/itemCount);

     if (col != 6) {
        col = 3;
     }
/*
    let shId = Array();

    for (let i = 0; i < itemCount; i++) {
      shId[i] = makeid();
    }*/
    
    const resultItems = arr.map((item, i) => {
        return (
            <div key={i} className={"col-xs-12 col-sm-6 col-md-6 col-lg-"+col+" text-center item"} data-toggle="modal" data-target={"#Modal_"+props.id+""+i}>
                <img className="img-responsive img-circle cursor-pointer" src={item.photo} alt={item.name}/>
                <p className="name">{item.name}</p>
                <p className="position">{item.position}</p>
            </div>
        )
    });

    const resultModalItems = arr.map((item, i) => {
        return (
            <div key={i} className="modal fade bd-example-modal-lg" id={"Modal_"+props.id+""+i} >
                <div className="modal-dialog modal-lg" role="document">
                    <div className="modal-content">
                        <div className="modal-body">
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><em className="fa fa-times" aria-hidden="true"></em></span>
                            </button>
                            <div className="modal-info-container container-fluid">
                                <div className="row">
                                    <div className="col-xs-10 col-xs-offset-1 col-md-10 col-lg-10 col-lg-offset-1">
                                        <div className="item" id={"id"+item.id}>
                                            <div className="wrapper-photo">
                                                <img className="img-responsive img-circle" src={item.photo}/>
                                            </div>
                                            <div className="info">
                                                <div className="top">
                                                    <span className="name">{item.name}</span><br />
                                                    <span className="thepost">{item.position}</span>
                                                    <br />
                                                    <div className="border"></div>
                                                </div>
                                                <div className="middle">
                                                    <div className="middle">{item.description}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="questions" dangerouslySetInnerHTML={{__html: item.questions}}/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    });

    return(
         <div className="container-fluid">
            <div className="row directors-grid">
                <div className="col-xs-12 col-xs-offset-0 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                    <div className="row flex-wrapper-grid">
                        {resultItems}
                    </div>
                </div>
            </div>
            <div className="row">
                {resultModalItems}
            </div>
        </div>
    )
}

export default PersonBlock
