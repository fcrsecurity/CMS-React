// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

// @ts-ignore
import styles from './StatisticWrapper.scss';

import { toLocaleString } from '../../utils';

import { StatisticWrapper } from './StatisticWrapper';


/**
 * @augments {React.Component<{
		icon: string;
		color: 1|2|3|4;
		title: string;
		km1: number;
		km3: number;
		km5: number;
		description?: string;
		money?: boolean;
	}>}
 */
export class Statistic extends React.Component {

	static propTypesOwn = {
		km1: PropTypes.number.isRequired,
		km3: PropTypes.number.isRequired,
		km5: PropTypes.number.isRequired,
	};

	static propTypes = {
		icon: PropTypes.string.isRequired,
		color: PropTypes.number.isRequired,
		title: PropTypes.string.isRequired,
		km1: PropTypes.number.isRequired,
		km3: PropTypes.number.isRequired,
		km5: PropTypes.number.isRequired,
		description: PropTypes.string,
		money: PropTypes.bool,
	};

	static defaultProps = {
		money: false,
		description: ''
	};

	toLocaleString(value) {
		return toLocaleString(value, this.props.money ? '$' : '');
	}

	render() {

		const { icon, title, km1, km3, km5, color, description } = this.props;

		return (
			<StatisticWrapper icon={ icon } color={ color }>
				<header>{ title }</header>
				<div><b>0-1KM:</b> { this.toLocaleString(km1) }</div>
				<div><b>0-3KM:</b> { this.toLocaleString(km3) }</div>
				<div><b>0-5KM:</b> { this.toLocaleString(km5) }</div>
				{
					description && (
						<small className={ styles.smallcenter }>{ description }</small>
					)
				}
			</StatisticWrapper>
		);
	}
}
