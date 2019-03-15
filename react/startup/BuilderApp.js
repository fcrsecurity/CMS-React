import React from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import TextWidget from '../widgets/TextWidget'

/* Default props for widget */
let wprops = {
    "isLoggedIn": true,
    "data": {"title": "ere",
        "subtitle": "ere"
    }
}

/* List of widgets */
let widgets = [
    <TextWidget {...wprops}/>
]
const WidgetList = ({widgets}) => {
    let i = 0;
    const widgetNode = widgets.map( (widget) => {
        i++;
        return <div key={i} draggable="true">{widget}</div>
    });
    return (
        <div>
            {widgetNode}
        </div>
    );
}
const Sidbar = () => {
    return (
        <div className="col-md-3">
            <h1>Widget List</h1>
            <WidgetList widgets={widgets}/>
        </div>
    );
}

class Layout extends React.Component{
    handleRowListChange() {
        console.log("dddd")
    }
    render(){
        return(
            <div className="col-md-9">
                <RowList myFunc={this.handleRowListChange} />
            </div>
        )
    }
}

class Row extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            rowID : this.props.rowID,
            colID : this.props.colID,
        }
    }

    render(){
        return (
            <div id={"R"+this.state.rowID} className="row row-builder">
                Row {this.state.rowID}
                <ColList rowID={this.state.rowID} />
            </div>
        );
    }
}

class Column extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            rowID : this.props.rowID,
            colID : this.props.colID
        }
    }

    render(){
        return (
            <div id={"R" + this.state.rowID + "C" + this.state.colID} className="col-builder col-md-3">
                Column {this.state.rowID + this.state.colID.toString()}
                <RowList rowID={this.state.rowID}
                         colID={this.state.colID}
                />
            </div>
        );
    }
}

class ColList extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            cols : [],
            colID : 0,
            rowID : this.props.rowID
        }
    }

    addCol(){
        this.state.colID++;
        const col = {colID: this.state.colID};
        this.state.cols.push(col);
        this.setState({data: this.state.cols});
    }

    render(){
        const colNode = this.state.cols.map((col) => {
            return (
                <Column colID={col.colID}
                        rowID={this.state.rowID}
                        key={this.state.rowID*10 + col.colID}
                />
            )
        });
        return (
            <div>
                {colNode}
                <a id="addCol"
                        onClick={() => {
                            this.addCol();
                        }}>
                    Add Col
                </a>
            </div>
        );
    }
}

class RowList extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            rows : [],
            rowID : (typeof this.props.rowID == 'undefined') ? 0 : this.props.rowID,
            colID : (typeof this.props.colID == 'undefined') ? 0 : this.props.colID,
        }
    }

    addRow(){
        console.log(ReactDOM.findDOMNode(this).parentNode.getAttribute("id"))
        this.state.rowID++;
        const row = {rowID: this.state.rowID,
                     colID: this.state.colID
        };
        this.state.rows.push(row);
        this.setState({data: this.state.rows});
        this.props.myFunc();
    }

    componentDidUpdate(){
        console.log( this.state)
    }
    render(){
        const rowNode = this.state.rows.map((row) => {
            return (<Row rowID={row.rowID}
                         colID={row.colID}
                         key={row.rowID}
                         ref="row"
            />)
        });
        return (
            <div className="widgets_content_area">
                {rowNode }
                <a id="addRow"
                        onClick={() => {
                            this.addRow();
                        }}>
                    Add Row
                </a>
            </div>
        );
    }
}
RowList.propTypes = {
    myFunc: React.PropTypes.func,
}

class BuilderApp extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            data: {
            }
        }
    }

    addRowToLayout(row) {
        let layout = this.state.data;
        layout[row] = "";
        this.setState({data: layout});
        console.log(layout)
    }

    render(){
        return (
            <div className="row">
                <Sidbar />
                <Layout />
            </div>
        );
    }
}

ReactDOM.render(<BuilderApp />, document.getElementById('builder'));