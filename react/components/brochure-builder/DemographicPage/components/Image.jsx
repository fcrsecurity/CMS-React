// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

import { InlineImageEditor } from '../../common/InlineImageEditor';
import { pdfPageHeight, pdfPageWidth } from '../../variables';

// @ts-ignore
import styles from './Image.scss';

/**
 * @augments {React.Component<{
		m: any;
		propertyId: number;
		errors: any;
		src?: string;
		imageMeta?: any;
	}>}
 */
export class Image extends React.Component {

	static propTypes = {
		m: PropTypes.any.isRequired,
		propertyId: PropTypes.number.isRequired,
		errors: PropTypes.any.isRequired,
		src: PropTypes.string,
		imageMeta: PropTypes.any,
	};

	getImage() {
		return this.image.getData();
	}

	render() {
		return (
			<div className={ styles.content }>
				<InlineImageEditor
					m={ this.props.m }
					propertyId={ this.props.propertyId }
					error={ !!this.props.errors['demographic.image'] }
					ref={ (ref) => this.image = ref }
					spec={ true }
					image={ this.props.src }
					imageMeta={ this.props.imageMeta }
					height={ pdfPageHeight - 380 }
					width={ pdfPageWidth } />
			</div>
		);
	}
}
