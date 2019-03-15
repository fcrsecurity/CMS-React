import React from 'react'

export default class ContentEditable extends React.Component {
    constructor (props) {
        super(props);
        this._onChange  = this._onChange.bind(this);
        this._onPaste   = this._onPaste.bind(this);
    }

    _onChange (ev) {
        const method  = this.getInnerMethod();
        const value   = this.node[method];
        const elem = this.props.el;
        const parent = this.props.parent;
        const gparent = this.props.gparent;
        this.props.onChange(ev, value, elem, parent, gparent);
    }

    _onPaste(ev) {
        let contentEditable  = this.props.contentEditable;
        let onPaste  = this.props._onPaste;

        if (contentEditable === 'plaintext-only') {
            ev.preventDefault();
            var text = ev.clipboardData.getData("text");
            document.execCommand('insertText', false, text);
        }

        if (onPaste) {
            onPaste(ev);
        }
    }

    getInnerMethod () {
        return this.props.contentEditable === 'plaintext-only' ? 'innerText' : 'innerHTML';
    }

    shouldComponentUpdate(nextProps, nextState) {
        const method = this.getInnerMethod();
        return nextProps.html !== this.node[method];
    }

    render () {
        const html  = this.props.html;
        const contentEditable  = this.props.contentEditable;
        const props = Object.assign({}, this.props);
        delete props.html;
        delete props.contentEditable;
        delete props.el;
        delete props.parent;
        delete props.gparent;

        return (
            <div
                {...props}
                ref={node => {this.node = node;} }
                dangerouslySetInnerHTML={{__html: html}}
                contentEditable={ false }
                onInput={ this._onChange }
                onPaste={ this._onPaste } >
            </div>
        )
    }
}