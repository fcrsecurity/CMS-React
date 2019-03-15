// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
import cn from 'classnames';

// @ts-ignore
import styles from './Spinner.scss';

/**
 * @augments {React.Component<{
		white?: boolean;
	}>}
 */
export class Spinner extends React.Component {

	static propTypes = {
		white: PropTypes.bool
	};

	static defaultProps = {
		white: false
	};

	render() {
		const dot = <div className={ cn(styles.dot, { [styles.dot__white]: this.props.white }) } />;
		return (
			<div className={ styles.content }>
				{ dot }
				{ dot }
				{ dot }
			</div>
		);
	}
}
