import React from 'react'

const Result = (props) => {

    let arr = Object.values(props.items);
    let itemCount = arr.length;
    let col = Math.round(12/itemCount);

    const resultItems = arr.map((item, i) => {
        return (
			<div key={i} className={"col-xs-12 col-sm-12 col-md-"+col+" col-lg-"+col+" text-center"}>
				<div className="thumbnails">
					<img src={item.icon} width="124" alt="{item.title}" /><br />
					<span className="line1">{item.title}</span><br />
					<span className="line2">{item.subtitle}</span>
				</div>
			</div>
        )
    });

    const Title = (str) => {
    	if(str.length !== 0){
    		return(
				<h2>{str}</h2>
			)
		}
	}
    const SubTitle = (str) => {
    	if(str.length !== 0){
    		return(
				<p>{str}</p>
			)
		}
	}

    return(
		<section className={"results " + props.config.wrapClasses + ' ' +
										props.config.widgetClasses +
										" container-fluid row"}>
			<div className="row">
				<div className="col-lg-12 text-center">
					{Title(props.title)}
					{SubTitle(props.subtitle)}
				</div>
			</div>

			<div className="row">
				<div className="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 buffer-4-botom">
					<div className={"row slick-"+itemCount+"-item-line"}>
                        {resultItems}
					</div>
				</div>
			</div>
		</section>
    )
}

export default Result