import React from 'react'
import ContentEditable from '../ContentEditable'

const Result = (props) => {

    let arr = Object.values(props.items),
    	itemCount = arr.length,
    	col = Math.round(12/itemCount);

    const resultItems = arr.map((item, i) => {
        let idx = i+1;
        return (
			<div key={i} className={"col-xs-12 col-sm-12 col-md-"+col+" col-lg-"+col+" text-center"}>
				<div className="thumbnails">
					<img src={item.icon} width="124" /><br />
					<ContentEditable
						el="title"
						parent={"item"+idx}
						html={item.title}
						className="line1 inline-mode"
						onChange={ props._handleChange }
						contentEditable="plaintext-only"
					/>
					<br />
					<ContentEditable
						el="subtitle"
						parent={"item"+idx}
						html={item.subtitle}
						className="line2 inline-mode"
						onChange={ props._handleChange }
						contentEditable="plaintext-only"
					/>
				</div>
			</div>
        )
    });

    const Title = (str) => {
    	if(str.length !== 0){
    		return(
				<ContentEditable
					el="title"
					html={str}
					className="h2 inline-mode"
					onChange={ props._handleChange }
					contentEditable="plaintext-only"
				/>
			)
		}
	}

	const SubTitle = (str) => {
    	if(str.length !== 0){
    		return(
				<ContentEditable
					el="subtitle"
					html={str}
					className="p inline-mode"
					onChange={ props._handleChange }
					contentEditable="plaintext-only"
				/>
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
				<div className="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
					<div className={"row slick-"+itemCount+"-item-line"}>
                        {resultItems}
					</div>
				</div>
			</div>
		</section>
    )
}

export default Result