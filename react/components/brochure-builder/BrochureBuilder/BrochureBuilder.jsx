// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
import cn from 'classnames';
import validate from 'validate.js';

// @ts-ignore
import styles from './BrochureBuilder.scss';

import { getLang } from '../messages';
import { assign, delay } from '../utils';

import { Button } from '../common/Button';
import { Errors } from '../common/Errors';

import { CoverPage } from '../CoverPage';
import { SitePlanPage } from '../SitePlanPage';
import { DemographicPage } from '../DemographicPage';
import { ContactPage } from '../ContactPage';

/**
 * @type {{
		image: string|null;
		imageMeta: {
			scale?: number;
			x?: number;
			y?: number;
		}|null;
	}}
 */
const image = null; // eslint-disable-line no-unused-vars

/**
 * @type {{
		propertyId: number;
		name: string;
		description: string;
		hideTagLine: boolean;
		lang: string;

		city: string;
		province: string;
		postal: string;
		intersection: string;
		address1: string;
		address2?: string;

		heroImage: typeof image|null;
		contactImage: typeof image|null;
		contactLifestyleImage: typeof image|null;

		demographic: typeof DemographicPage.props;

		latitude: number;
		longitude: number;
		zoom?: number;

		officeHeader: string;
		officeLine1: string;
		officeLine2: string;

		contacts: any[];
		tenants: string[];
		plans: Array<{
			image: string;
			imageMeta?: {
				scale?: number;
				x?: number;
				y?: number;
			};
		}>;
	}}
 */
const props = null; // eslint-disable-line no-unused-vars

let fileManagerLast = null;

/**
 * @augments {React.Component<{
		cancelPath: string;
		resultPath: string;
		savePath: string;
		requestPath: string;
		offices: any[];
		props: typeof props;
	}, {
		saving: boolean;
		errors: {
			[key: string]: string;
		},
		props: typeof props;
	}>}
 */
export class BrochureBuilder extends React.Component {

	static google = null;
	static FileManager = null;
	static fileManagerPath = null;

	static propTypes = {
		cancelPath: PropTypes.string.isRequired,
		resultPath: PropTypes.string.isRequired,
		savePath: PropTypes.string.isRequired,
		requestPath: PropTypes.string.isRequired,
		offices: PropTypes.arrayOf(PropTypes.any.isRequired).isRequired,
		props: PropTypes.shape({
			propertyId: PropTypes.number.isRequired,
			lang: PropTypes.string.isRequired,
			latitude: PropTypes.number.isRequired,
			longitude: PropTypes.number.isRequired,
			zoom: PropTypes.number,
			plans: PropTypes.arrayOf(
				PropTypes.shape({
					image: PropTypes.string.isRequired,
					imageMeta: PropTypes.any
				}).isRequired
			).isRequired,
			demographic: DemographicPage.propTypes.props,
		}).isRequired
	};

	mounted = false;
	coverPage = null;
	demographicPage = null;
	contactPage = null;

	state = {
		saving: false,
		errors: {},
		props: this.props.props
	};

	plans = {};

	static openFileManager = (propertyId, cb) => {
		if (fileManagerLast) {
			fileManagerLast.close();
		}

		fileManagerLast = new BrochureBuilder.FileManager(
			BrochureBuilder.fileManagerPath + (propertyId ? ((-1 < BrochureBuilder.fileManagerPath.indexOf('?') ? '&' : '?') + 'property=' + propertyId) : '')
		);

		fileManagerLast.open(cb);
	}

	componentDidMount() {
		this.mounted = true;
	}

	componentWillUnmount() {
		this.mounted = false;
	}

	setStateSafe(state) {
		return new Promise((resolve) => {
			if (this.mounted) {
				this.setState(state, () => resolve());
			} else {
				resolve();
			}
		});
	}

	/**
	 * @type {(index: number) => () => void}
	 */
	handleSitePlanPageDelete = (index) => () => {
		this.setState(({ props }) => {
			if (0 < props.plans.length) {
				const result = [...props.plans];
				result.splice(index, 1);
				return {
					props: assign(props, {
						plans: result
					})
				};
			} else {
				return {
					props
				};
			}
		});
	}

	/**
	 * @type {() => void}
	 */
	handleSitePlanPageAdd = () => {
		BrochureBuilder.openFileManager(this.props.props.propertyId, (file) => {
			if (file && file.url) {
				if (this.mounted) {
					this.setState(({ props }) => {
						const result = [...props.plans];
						result.push({
							image: file.url
						});
						return {
							props: assign(props, {
								plans: result
							})
						};
					});
				}
			}
		});
	}

	/**
	 * @type {() => void}
	 */
	handleCancel = () => {
		window.location.href = this.props.cancelPath;
	}

	isImage = (data) => {
		return !validate.isEmpty(data) &&
			!validate.isEmpty(data.crop) &&
			!validate.isEmpty(data.meta) &&
			!validate.isEmpty(data.src)
		;
	}

	validate = (data) => {
		const errors = {};
		if (data) {
			if (!validate.isArray(data.plans) || 0 === data.plans.length) {
				errors.plans = 'empty';
			}
			if (validate.isEmpty(data.cover.description)) {
				errors['cover.description'] = 'empty';
			}
			if (!this.isImage(data.cover.image)) {
				errors['cover.image'] = 'empty';
			}
			if (null !== data.contact.image && !this.isImage(data.contact.image)) {
				errors['contact.image'] = 'empty';
			}
			if (!this.isImage(data.contact.lifestyleImage)) {
				errors['contact.lifestyle'] = 'empty';
			}
			if (!this.isImage(data.demographic.image)) {
				errors['demographic.image'] = 'empty';
			}
		} else {
			errors.global = 'error';
		}

		return errors;
	}

	getErrorsFromException = (error) => {
		const errors = {};
		if (error instanceof DOMException) {
			errors.domexception = 'error';
		} else {
			errors.exception = error.message;
		}
		return errors;
	}

	/**
	 * @type {(request: boolean) => void}
	 */
	handleSave = async (request) => {

		try {
			await this.setStateSafe({
				[request ? 'requesting' : 'saving']: true,
				errors: {}
			});

			await delay(1);

			const body = {
				cover: this.coverPage.getData(),
				plans: this.state.props.plans.map((item, index) => {
					if (this.plans['~' + index]) {
						return this.plans['~' + index].getData();
					}
				}).filter((item) => !!item),
				demographic: this.demographicPage.getData(),
				contact: this.contactPage.getData()
			};

			const errors = this.validate(body);
			await this.setStateSafe({
				errors
			});

			if (0 === Object.keys(errors).length) {
				const result = await fetch(request ? this.props.requestPath : this.props.savePath, {
					method: 'POST',
					credentials: 'same-origin',
					headers: new Headers({
						'Content-Type': 'application/json',
						'X-Requested-With' : 'XMLHttpRequest'
					}),
					body: JSON.stringify(body)
				});

				if (result) {
					if (200 === result.status) {
						window.location.href = this.props.resultPath;
						return;
					} else if (422 === result.status) {
						const json = await result.json();
						if (json && !json.result && json.errors) {
							await this.setStateSafe({
								saving: false,
								errors: json.errors
							});
							return;
						} 
					}
				}

				throw new Error('Invalid Response');
			}
		} catch (error) {
			const errors = this.getErrorsFromException(error);
			await this.setStateSafe({
				errors
			});
		}

		await this.setStateSafe({
			saving: false,
			requesting: false,
		});
	}

	handleSaveWithoutApprove = async () => this.handleSave(false)

	handleSaveAndApprove = async () => this.handleSave(true)

	render() {
		const { props, saving, requesting, errors } = this.state;
		const { intersection, demographic, plans, contacts, tenants } = props;
		const { propertyId, name, lang, city, province, postal, address1, address2, description, hideTagLine } = props;
		
		const defaultImage = { image: '', imageMeta: null };
		const { heroImage, contactImage, contactLifestyleImage } = props;
		const { image: heroImageSrc, imageMeta: heroImageMeta } = heroImage || defaultImage;
		const { image: contactImageSrc, imageMeta: contactImageMeta } = contactImage || defaultImage;
		const { image: contactLifestyleImageSrc, imageMeta: contactLifestyleImageMeta } = contactLifestyleImage || defaultImage;

		const { latitude, longitude, zoom } = props;
		const { officeHeader, officeLine1, officeLine2 } = props;

		const { offices } = this.props;

		const m = getLang(lang);

		this.plans = {};
		return (
			<div className="BrochureBuilder">
				<div className={ cn(styles.content, { [styles.saving]: saving || requesting }) }>
					<CoverPage
						m={ m }
						propertyId={ propertyId }
						errors={ errors }
						ref={ (ref) => this.coverPage = ref }
						image={ heroImageSrc }
						imageMeta={ heroImageMeta }
						name={ name }
						city={ city }
						province={ province }
						description={ description }
						hideTagLine = { hideTagLine }
						tenants={ tenants }
					/>
					{
						plans.map(({ image, imageMeta }, index) => (
							<SitePlanPage
								m={ m }
								ref={ (ref) => this.plans['~' + index] = ref }
								propertyId={ propertyId }
								key={ image + index }
								image={ image }
								imageMeta={ imageMeta }
								onDelete={ this.handleSitePlanPageDelete(index) }
							/>
						))
					}
					<div className={ styles.add_button }>
						<Button onClick={ this.handleSitePlanPageAdd }>
							{
								plans.length ? m.buttons.add_more_site_plans : m.buttons.add_site_plan
							}
						</Button>
					</div>
					<DemographicPage
						m={ m }
						propertyId={ propertyId }
						errors={ errors }
						ref={ (ref) => this.demographicPage = ref }
						intersection={ intersection }
						props={ demographic }
					/>
					<ContactPage
						m={ m }
						propertyId={ propertyId }
						errors={ errors }
						ref={ (ref) => this.contactPage = ref }
						image={ contactImageSrc }
						imageMeta={ contactImageMeta }
						lifestyleImage={ contactLifestyleImageSrc }
						lifestyleImageMeta={ contactLifestyleImageMeta }
						name={ name }
						city={ city }
						province={ province }
						postal={ postal }
						address1={ address1 }
						address2={ address2 }
						contacts={ contacts }
						offices={ offices }
						officeHeader={ officeHeader }
						officeLine1={ officeLine1 }
						officeLine2={ officeLine2 }
						latitude={ latitude }
						longitude={ longitude }
						zoom={ zoom }
					/>
					{
						errors && 0 < Object.keys(errors).length && (
							<Errors m={ m } errors={ errors } />
						)
					}
					<div className={ styles.save_button }>
						<Button onClick={ this.handleCancel }>
							{ m.buttons.cancel }
						</Button>
						<Button onClick={ this.handleSaveWithoutApprove }>
							{ saving ? m.buttons.saving : m.buttons.save }
						</Button>
						<Button onClick={ this.handleSaveAndApprove }>
							{ requesting ? m.buttons.requesting : m.buttons.request }
						</Button>
					</div>
				</div>
			</div>
		);
	}
}
