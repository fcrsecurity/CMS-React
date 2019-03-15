// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

import { Page } from '../common/Page';

import { Header } from './components/Header';
import { MarketingSection } from './components/MarketingSection';
import { TenantsSection } from './components/TenantsSection';
import { TenantLogo } from './components/TenantLogo';

/**
 * @augments {React.Component<{
		m: any;
		propertyId: number;
		errors: any;
		name: string;
		city: string;
		province: string;
		image: string;
		imageMeta: any;
		description: string;
		hideTagLine: boolean;
		tenants: string[];
	}, {
		tenants: string[];
	}>}
 */
export class CoverPage extends React.Component {

	static propTypes = {
		m: PropTypes.any.isRequired,
		propertyId: PropTypes.number.isRequired,
		errors: PropTypes.any.isRequired,
		name: PropTypes.string.isRequired,
		city: PropTypes.string.isRequired,
		province: PropTypes.string.isRequired,
		image: PropTypes.string,
		imageMeta: PropTypes.any,
		description: PropTypes.string.isRequired,
		hideTagLine: PropTypes.bool.isRequired,
		tenants: PropTypes.arrayOf(PropTypes.string).isRequired
	};

	state = {
		tenants: this.props.tenants.length > 2 ? this.props.tenants.slice(0, 6) : []
	};

	header = null;
	marketing = null;

	reorderTenants(list, startIndex, endIndex) {
		const result = Array.from(list);
		const [removed] = result.splice(startIndex, 1);
		result.splice(endIndex, 0, removed);
		return result;
	}

	onDragEnd = (result) => {
		// dropped outside the list
		if (!result.destination) {
			return;
		}
	
		const tenants = this.reorderTenants(
			this.state.tenants,
			result.source.index,
			result.destination.index
		);
	
		this.setState({
			tenants
		});
	}

	getData() {
		return {
			tenants: this.state.tenants,
			image: this.header.getImageData(),
			hideTagLine: this.marketing.getHideTagLine(),
			description: this.marketing.getDescription()
		};
	}

	render() {

		const { m, propertyId, name, city, province } = this.props;
		const { image, imageMeta, description, hideTagLine } = this.props;
		const { errors } = this.props;
		const { tenants } = this.state;

		return (
			<Page>
				<Header
					m={ m }
					propertyId={ propertyId }
					errors={ errors }
					ref={ (ref) => this.header = ref }
					name={ name }
					city={ city }
					province={ province }
					image={ image }
					imageMeta={ imageMeta }
				/>
				<MarketingSection
					m={ m }
					hideTagLine = { hideTagLine }
					ref={ (ref) => this.marketing = ref }
					description={ description }
				/>
				<TenantsSection onDragEnd={ this.onDragEnd }>
					{
						tenants.map((image, index) => (
							<TenantLogo key={ image } image={ image } index={ index } />
						))
					}
				</TenantsSection>
			</Page>
		);
	}
}
