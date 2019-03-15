// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

const icons = {
	// @ts-ignore
	population: require('./assets/population.png'),
	// @ts-ignore
	car: require('./assets/car.png'),
	// @ts-ignore
	house: require('./assets/house.png'),
	// @ts-ignore
	income: require('./assets/income.png')
};

import { Page } from '../common/Page';

import { Image } from './components/Image';
import { Wrapper } from './components/Wrapper';
import { Statistic } from './components/Statistic';
import { TrafficCount } from './components/TrafficCount';

/**
 * @type {{
		image: {
			image: string|null;
			imageMeta: {
				scale?: number;
				x?: number;
				y?: number;
			}|null;
		}|null,
		stats: {
			trafficCount: number;
			population: {
				km1: number;
				km3: number;
				km5: number;
			};
			avgHouseholdIncome: {
				km1: number;
				km3: number;
				km5: number;
			};
			totalHouseholds: {
				km1: number;
				km3: number;
				km5: number;
			};
		};
	}}
 */
const props = null; 

/**
 * @augments {React.Component<{
 		m: any;
 		propertyId: number;
		errors: any;
		intersection: string;
		props: typeof props;
	}>}
 */
export class DemographicPage extends React.Component {

	static props = props;

	static propTypes = {
		m: PropTypes.any.isRequired,
		propertyId: PropTypes.number.isRequired,
		errors: PropTypes.any.isRequired,
		intersection: PropTypes.string.isRequired,
		props: PropTypes.shape({
			image: PropTypes.shape({
				image: PropTypes.string,
				imageMeta: PropTypes.any
			}),
			stats: PropTypes.shape({
				population: PropTypes.shape(Statistic.propTypesOwn).isRequired,
				avgHouseholdIncome: PropTypes.shape(Statistic.propTypesOwn).isRequired,
				totalHouseholds: PropTypes.shape(Statistic.propTypesOwn).isRequired,
				trafficCount: PropTypes.number.isRequired,
			})
		})
	};

	image = null;

	getData() {
		return {
			image: this.image.getImage()
		};
	}

	render() {

		const {
			m,
			errors,
			intersection,
			propertyId,
			props: {
				image,
				stats: {
					population, avgHouseholdIncome, totalHouseholds, trafficCount
				}
			}
		} = this.props;

		const {
			image: imageSrc,
			imageMeta
		} = image || { image: '', imageMeta: null };

		return (
			<Page>
				<Image
					m={ m }
					propertyId={ propertyId }
					errors={ errors }
					ref={ (ref) => this.image = ref }
					src={ imageSrc }
					imageMeta={ imageMeta }
				/>
				<Wrapper>
					<Statistic
						color={ 1 }
						icon={ icons.population }
						title={ m.interface.demographic.population + ' *' }
						km1={ population.km1 }
						km3={ population.km3 }
						km5={ population.km5 }
						description={ '* ' + m.interface.demographic.demographics_2017 }
					/>
					<Statistic
						color={ 2 }
						icon={ icons.income }
						title={ m.interface.demographic.avg_household_income + ' *' }
						km1={ avgHouseholdIncome.km1 }
						km3={ avgHouseholdIncome.km3 }
						km5={ avgHouseholdIncome.km5 }
						money={ true }
					/>
					<Statistic
						color={ 3 }
						icon={ icons.house }
						title={ m.interface.demographic.total_households + ' *' }
						km1={ totalHouseholds.km1 }
						km3={ totalHouseholds.km3 }
						km5={ totalHouseholds.km5 }
					/>
					<TrafficCount
						m={ m }
						color={ 4 }
						icon={ icons.car }
						intersection={ intersection }
						count={ trafficCount }
					/>
				</Wrapper>
			</Page>
		);
	}
}
