// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

// @ts-ignore
import styles from './Page.scss';

/**
 * @augments {React.Component}
 */
export class Page extends React.Component {

	static propTypes = {
		children: PropTypes.node.isRequired
	};

	render() {
		return (
			<div className={ styles.content }>
				{ this.props.children }
			</div>
		);
	}
}
