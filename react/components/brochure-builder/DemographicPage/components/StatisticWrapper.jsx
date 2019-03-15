// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
import cn from 'classnames';

// @ts-ignore
import styles from './StatisticWrapper.scss';

/**
 * @augments {React.Component<{
		icon?: string;
		color: 1|2|3|4;
		center?: boolean;
		iconFix?: boolean;
	}>}
 */
export class StatisticWrapper extends React.Component {
	
	static propTypes = {
		children: PropTypes.node.isRequired,
		icon: PropTypes.string,
		color: PropTypes.number.isRequired,
		center: PropTypes.bool,
		iconFix: PropTypes.bool
	};

	static defaultProps = {
		center: false
	};

	render() {
		return (
			<li className={ cn(styles.content, styles['content__clr_' + this.props.color]) }>
				<div className={ styles.section }>
					{
						this.props.icon && (
							<div className={ cn(styles.icon, { [styles.icon__fix]: this.props.iconFix }) }>
								<img src={ this.props.icon } />
							</div>
						)
					}
					<div className={ cn(styles.info, { [styles.info__center]: this.props.center }) }>
						{ this.props.children }
					</div>
				</div>
			</li>
		);
	}
}
