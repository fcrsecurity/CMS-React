// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

// @ts-ignore
import styles from './Wrapper.scss';

/**
 * @augments {React.Component}
 */
export class Wrapper extends React.Component {
	
	static propTypes = {
		children: PropTypes.node.isRequired
	};

	render() {
		return (
			<ul className={ styles.content }>
				{ this.props.children }
			</ul>
		);
	}
}
