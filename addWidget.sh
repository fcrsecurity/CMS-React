#!/bin/bash

# Set Default Variables
projectsPath="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "Enter widget name:"
read widgetName

projectsPathReact=$projectsPath/react

# startup/xxxClient.js
echo "import React from 'react'
import "$widgetName"Widget from '../widgets/"$widgetName"Widget'

export default (initialProps) => {
    return (
        <div>
            <"$widgetName"Widget {...initialProps} />
        </div>
    )
}" > $projectsPathReact/startup/$widgetName''Client.js

# startup/xxxServer.js
echo "import React from 'react'
import "$widgetName"Widget from '../widgets/"$widgetName"Widget'

export default (initialProps) => {
    return (
        <div>
            <"$widgetName"Widget {...initialProps} />
        </div>
    )
}" > $projectsPathReact/startup/$widgetName''Server.js

# widgets/xxxWidget.js
echo "import React from 'react'
import Client"$widgetName"Component from '../components/client/"$widgetName"'
import Admin"$widgetName"Component from '../components/admin/"$widgetName"'

export default class "$widgetName"Widget extends React.Component {
    constructor(props) {
        super(props)
        if (this.props) {
            // Props from twig macros
            this.state = {
                title: this.props.title,
                subtitle: this.props.subtitle,
                items: this.props.items,
                isLoggedIn: this.props.isLoggedIn,
                config: this.props.config
            }
        }
        else {
            this.state = {
                data: null
            }
        }
    }

    render() {
        const isLoggedIn = this.state.isLoggedIn;

        let W = null;
        if (isLoggedIn) {
            W = <Admin"$widgetName"Component {...this.state} />;
        }
        else {
            W = <Client"$widgetName"Component {...this.state} />;
        }
        return(
            <div className=\"widgetWrapper {this.props.styleName}\">
                {W}
            </div>
        )
    }
}
" > $projectsPathReact/widgets/$widgetName''Widget.js

# components/Admin/xxx.js
echo "import React from 'react'

const "$widgetName" = (props) => {

    // Example from result widget

    var arr = Object.values(props.items);
    var itemCount = arr.length;
    var col = Math.round(12/itemCount);
    var color = props.config.color

    // Loop to collect items in resultItems variable
    const resultItems = arr.map((item, i) => {
        return (
            <div key={i} className={\"col-xs-12 col-sm-12 col-md-\"+col+\" col-lg-\"+col+\" text-center slick-slide slick-current slick-active\"}>
                <div className=\"thumbnails\">
                    <img src={item.icon} width=\"124\" /><br />
                    <span className=\"line1\">{item.title}</span><br />
                    <span className=\"line2\">{item.subtitle}</span>
                </div>
            </div>
        )
    });

    return(
        <section className=\"results container-fluid\">
            <div className=\"row buffer-6-top\">
                <div className=\"col-lg-12 text-center\">
                    <h1>{props.title}</h1>
                    <p>{props.subtitle}</p>
                </div>
            </div>

            <div className=\"row\">
                <div className=\"col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 buffer-4-botom\">
                    <div className=\"row slick-3-item-line slick-initialized slick-slider\">
                        <div aria-live=\"polite\" className=\"slick-list draggable\">
                            <div className=\"slick-track\" role=\"listbox\">
                                {resultItems}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    )
}

export default "$widgetName > $projectsPathReact/components/admin/$widgetName.js

# components/Admin/xxx.js
echo "import React from 'react'

const "$widgetName" = (props) => {

    // Example from result widget

    var arr = Object.values(props.items);
    var itemCount = arr.length;
    var col = Math.round(12/itemCount);
    var color = props.config.color

    // Loop to collect items in resultItems variable
    const resultItems = arr.map((item, i) => {
        return (
            <div key={i} className={\"col-xs-12 col-sm-12 col-md-\"+col+\" col-lg-\"+col+\" text-center slick-slide slick-current slick-active\"}>
                <div className=\"thumbnails\">
                    <img src={item.icon} width=\"124\" /><br />
                    <span className=\"line1\">{item.title}</span><br />
                    <span className=\"line2\">{item.subtitle}</span>
                </div>
            </div>
        )
    });

    return(
        <section className=\"results container-fluid\">
            <div className=\"row buffer-6-top\">
                <div className=\"col-lg-12 text-center\">
                    <h1>{props.title}</h1>
                    <p>{props.subtitle}</p>
                </div>
            </div>

            <div className=\"row\">
                <div className=\"col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 buffer-4-botom\">
                    <div className=\"row slick-3-item-line slick-initialized slick-slider\">
                        <div aria-live=\"polite\" className=\"slick-list draggable\">
                            <div className=\"slick-track\" role=\"listbox\">
                                {resultItems}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    )
}

export default "$widgetName > $projectsPathReact/components/client/$widgetName.js

# src/CraftKeen/CMS/ThemeBundle/Resources/views/FCR/Widgets/xxx.html.twig
echo "{% macro "$widgetName"(widget) %}
	{# There we can check users roles and another data that available in twig #}
	{{
		react_component(
			widget.dataType, {
				'props':{
					\"isLoggedIn\": false,
					\"title\": widget.data.title,
					\"subtitle\": widget.data.text,
					\"items\": widget.data.items,
					\"config\": widget.data.config
				}
			}
		)
	}}
{% endmacro %}" > $projectsPath/src/CraftKeen/CMS/ThemeBundle/Resources/views/FCR/Widgets/$widgetName.html.twig

# Prepare folder
mkdir -p $projectsPath/src/CraftKeen/CMS/ThemeBundle/Resources/public/FCR/src/scss/widgets/$widgetName
chown root:www-data -R $projectsPath/src/CraftKeen/CMS/ThemeBundle/Resources/public/FCR/src/scss/widgets/$widgetName
chmod 775 -R $projectsPath/src/CraftKeen/CMS/ThemeBundle/Resources/public/FCR/src/scss/widgets/$widgetName

# src/CraftKeen/CMS/ThemeBundle/Resources/public/FCR/src/scss/widgets/xxx/_xxx.scss
echo "@import \""$widgetName"_queries\";" > $projectsPath/src/CraftKeen/CMS/ThemeBundle/Resources/public/FCR/src/scss/widgets/$widgetName/_$widgetName.scss

# src/CraftKeen/CMS/ThemeBundle/Resources/public/FCR/src/scss/widgets/xxx/_xxx_queries.scss
echo "" > $projectsPath/src/CraftKeen/CMS/ThemeBundle/Resources/public/FCR/src/scss/widgets/$widgetName/_$widgetName''_queries.scss

# src/CraftKeen/CMS/ThemeBundle/Resources/public/FCR/src/scss/widgets/xxx/_xxx_queries.scss
echo "
@import \"$widgetName/$widgetName\";" >> $projectsPath/src/CraftKeen/CMS/ThemeBundle/Resources/public/FCR/src/scss/widgets/_widgets.scss

echo "Please include "$widgetName" widget in clientEntryPoint.js"
echo "Please include "$widgetName" widget in serverRegistration.js"
echo "Complete"