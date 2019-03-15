// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

import { pdfPageHeight, pdfPageWidth } from '../variables';

// @ts-ignore
import styles from './SitePlanPage.scss';

import { Page } from '../common/Page';


/**
 * @type {{
		onDelete?: () => void;
		m: any;
		propertyId: number;
		image: string;
		imageMeta?: {
			scale?: number;
			x?: number;
			y?: number;
		};
 	}}
 */
const props = null;

/**
 * @augments {React.Component<typeof props>}
 */
export class SitePlanPage extends React.Component {

	static props = props;

	static propTypes = {
		onDelete: PropTypes.func,
		m: PropTypes.any.isRequired,
		propertyId: PropTypes.number.isRequired,
		image: PropTypes.string.isRequired,
		imageMeta: PropTypes.any
	};

	image = null;

	handleOnDelete = () => {
		if (this.props.onDelete) {
			this.props.onDelete();
		}
	}

	renderActions() {
		return this.props.onDelete ? (
			<div className={ styles.actions }>
				{
					this.props.onDelete && (
						<button className={ styles.actions__delete } onClick={ this.handleOnDelete }>
							x
						</button>
					)
				}
			</div>
		) : null;
	}

	getData() {
		return this.image.getData();
	}

	render() {
		const { m, propertyId, image, imageMeta } = this.props;
		return (
			<Page>
				<div className={ styles.content }>
					<img src={ image } width={ pdfPageWidth } />
					{ this.renderActions() }
				</div>
			</Page>
		);
	}
}
