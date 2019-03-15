// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

import { toLocaleString } from '../../utils';

import { StatisticWrapper } from './StatisticWrapper';

// @ts-ignore
import styles from './TrafficCount.scss';

const icons = {
	// @ts-ignore
	logo: require('../assets/logo.png'),
};

/**
 * @augments {React.Component<{
		m: any;
		icon: string;
		color: 1|2|3|4;
		intersection: string;
		count: number;
	}>}
 */
export class TrafficCount extends React.Component {

	static propTypes = {
		m: PropTypes.any.isRequired,
		icon: PropTypes.string.isRequired,
		intersection: PropTypes.string.isRequired,
		count: PropTypes.number.isRequired,
		color: PropTypes.number.isRequired,
	};

	render() {

		const { m, icon, intersection, count, color } = this.props;

		if (count !== 0) {
			return (
				<StatisticWrapper icon={ icon } color={ color } center={ true } iconFix={ true }>
					<header>{ m.interface.demographic.traffic_count }</header>
					<small>{ intersection }</small>
					<div>{ toLocaleString(count) }</div>
					<div className={ styles.subline }>{ m.interface.demographic.aadt }</div>
				</StatisticWrapper>
			);
		}
		return (
			<StatisticWrapper color={ color }>
				<div className={ styles.nocount }>
					<img src={ icons.logo } />
				</div>
			</StatisticWrapper>
		);
	}
}
