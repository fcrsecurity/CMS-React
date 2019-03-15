// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
import cn from 'classnames';

import { pdfPageHeight, pdfPageWidth } from '../variables';
import { addr } from '../utils';

// @ts-ignore
import styles from './ContactPage.scss';

// // @ts-ignore
// import lifestyleDefault from './assets/lifestyle.jpg';

import { Page } from '../common/Page';
import { InlineImageEditor } from '../common/InlineImageEditor';
import { LocationMap } from './components/LocationMap';

/**
 * @augments {React.Component<{
		m: any;
		propertyId: number;
		errors: any;

		image?: string;
		imageMeta?: any;
		lifestyleImage?: string;
		lifestyleImageMeta?: any;

		name: string;
		city: string;
		province: string;
		postal: string;
		address1: string;
		address2?: string;

		offices: any[];
		contacts: any[];

		officeHeader: string;
		officeLine1: string;
		officeLine2: string;

		latitude: number;
		longitude: number;
		zoom?: number;
	}, {
		officeHeader: string,
		officeLine1: string,
		officeLine2: string
	}>}
 */
export class ContactPage extends React.Component {

	static propTypes = {
		m: PropTypes.any.isRequired,
		propertyId: PropTypes.number.isRequired,
		errors: PropTypes.any.isRequired,

		image: PropTypes.string,
		imageMeta: PropTypes.any,
		lifestyleImage: PropTypes.string,
		lifestyleImageMeta: PropTypes.any,

		name: PropTypes.string.isRequired,
		city: PropTypes.string.isRequired,
		province: PropTypes.string.isRequired,
		postal: PropTypes.string.isRequired,
		address1: PropTypes.string.isRequired,
		address2: PropTypes.string,

		offices: PropTypes.arrayOf(PropTypes.any).isRequired,
		contacts: PropTypes.arrayOf(PropTypes.any).isRequired,

		officeHeader: PropTypes.string.isRequired,
		officeLine1: PropTypes.string.isRequired,
		officeLine2: PropTypes.string.isRequired,

		latitude: PropTypes.number.isRequired,
		longitude: PropTypes.number.isRequired,
		zoom: PropTypes.number
	};

	state = {
		officeHeader: this.props.officeHeader,
		officeLine1: this.props.officeLine1,
		officeLine2: this.props.officeLine2
	}

	image = null;
	lifestyleImage = null;
	map = null;

	renderSizes() {

		const delimiter = 20;
		
		const firstColumnWidth = pdfPageWidth * 0.35;
		const secondColumnWidth = pdfPageWidth - firstColumnWidth - delimiter;

		const mapHeight = pdfPageHeight * 0.4;
		const contentHeight = pdfPageHeight - mapHeight - delimiter;

		const { contacts } = this.props;
		const firstColumnItemHeight = contacts && 2 <= contacts.length ? 0 : contentHeight * 0.45;
		const secondColumnItemHeight = contacts && 2 <= contacts.length ?
			contentHeight : contentHeight - firstColumnItemHeight - delimiter;

		return {
			firstColumnWidth,
			secondColumnWidth,
			contentHeight,
			mapHeight,
			firstColumnItemHeight,
			secondColumnItemHeight,
			delimiter
		};
	}

	getData() {
		return {
			office: {
				header: this.state.officeHeader,
				line1: this.state.officeLine1,
				line2: this.state.officeLine2
			},
			image: this.image ? this.image.getData() : null,
			lifestyleImage: this.lifestyleImage.getData(),
			location: this.map.getLocationData(),
			zoom: this.map.getZoomData()
		};
	}

	handleOfficeClick = (office) => () => {
		this.setState({
			officeHeader: office.header,
			officeLine1: office.line1,
			officeLine2: office.line2,
		});
	}

	renderContacts(mono) {
		const { contacts } = this.props;

		if (!contacts || !contacts.length) {
			return null;
		}

		const contact = contacts && contacts[0] ? contacts[0] : null;
		if (!contact) {
			return null;
		}
		
		return contacts.map((contact, index) => (
			<div key={ index }>
				<div className={ cn(styles.info__title, styles.light, { [styles.info__title__mono]: mono }) }>{ contact.firstName } { contact.lastName }</div>
				<div>{ contact.title }</div>
				<div>D: { contact.phone }</div>
				<div>{ contact.email }</div>
			</div>
		));
	}

	renderOffices(mono) {
		const { offices } = this.props;
		const { officeHeader, officeLine1, officeLine2 } = this.state;

		return 0 < offices.length ? (
			<div>
				<hr className={ cn(styles.info__delimiter, { [styles.info__delimiter__mono]: mono }) } />
				<div>
					<div className={ cn(styles.info__title, styles.light, { [styles.info__title__mono]: mono }) } data-toggle="dropdown">
						<div className="dropdown">
							{/* officeHeader */}
							First Capital Realty Inc.
							<span className={ cn('glyphicon', 'glyphicon-pencil', styles.info__action) } aria-hidden="true" data-toggle="dropdown"></span>
							<ul className="dropdown-menu dropdown-menu-right">
								{
									offices.map((item) => (<li key={ item.id }><a onClick={ this.handleOfficeClick(item) }>{ item.name }</a></li>))
								}
							</ul>
						</div>
					</div>
					<div dangerouslySetInnerHTML={{ __html: officeLine1 }} />
					<div>{ officeLine2 }</div>
				</div>
			</div>
		) : null;
	}

	render() {

		const {
			delimiter,
			firstColumnWidth, secondColumnWidth,
			contentHeight, mapHeight, firstColumnItemHeight, secondColumnItemHeight
		} = this.renderSizes();

		const dH = <div style={{ height: delimiter }}></div>;
		const dW = <div style={{ width: delimiter }}></div>;

		const { m, propertyId, image, imageMeta, lifestyleImage, lifestyleImageMeta } = this.props;
		const { latitude, longitude, zoom } = this.props;
		const { name, city, province, postal, address1, address2 } = this.props;
		const { errors } = this.props;

		const mono = 0 === firstColumnItemHeight;

		return (
			<Page>
				<div className={ styles.content } style={{ height: contentHeight }}>
					<div className={ styles.wrapper } style={{ width: firstColumnWidth }}>
						{
							!mono && (
								<div className={ styles.image } style={{ width: firstColumnWidth, height: firstColumnItemHeight }}>
									<InlineImageEditor
										m={ m }
										propertyId={ propertyId }
										error={ !!errors['contact.image'] }
										ref={ (ref) => this.image = ref }
										image={ image }
										imageMeta={ imageMeta }
										minScale={ 0.3 }
										height={ firstColumnItemHeight }
										width={ firstColumnWidth }
									/>
								</div>
							)
						}
						{ !mono && dH }
						<div className={ styles.info } style={{ width: firstColumnWidth, height: secondColumnItemHeight }}>
							<div style={{textTransform: 'uppercase'}}>
								<div className={ cn(styles.info__title, styles.bold, { [styles.info__title__mono]: mono }) }>{ name }</div>
								<div>{ address1 }</div>
								{
									address2 && (
										<div>{ address2 }</div>
									)
								}
								<div>{ addr(city, province, postal).join(', ') }</div>
							</div>
							<hr className={ cn(styles.info__delimiter, styles.info__delimiter__hidden) } />
							{ this.renderContacts(mono) }
							{ this.renderOffices(mono) }
						</div>
					</div>
					{ dW }
					<div className={ styles.lifestyle } style={{ width: secondColumnWidth }}>
						<InlineImageEditor
							m={ m }
							propertyId={ propertyId }
							error={ !!errors['contact.lifestyle'] }
							ref={ (ref) => this.lifestyleImage = ref }
							image={ lifestyleImage }
							imageMeta={ lifestyleImageMeta }
							height={ contentHeight }
							width={ secondColumnWidth }
						/>
					</div>
				</div>
				{ dH }
				<div className={ styles.map } style={{ height: mapHeight }}>
					<LocationMap ref={ (ref) => this.map = ref } longitude={ longitude } latitude={ latitude } zoom={ zoom } />
				</div>
			</Page>
		);
	}
}
