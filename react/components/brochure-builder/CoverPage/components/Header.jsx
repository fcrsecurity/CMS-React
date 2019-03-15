// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
// import Popover from 'react-popover';

import { pdfPageHeight, pdfPageWidth } from '../../variables';
import { addr } from '../../utils';

import { InlineImageEditor } from '../../common/InlineImageEditor';
// import { Button } from '../../common/Button';

// @ts-ignore
import styles from './Header.scss';
// @ts-ignore
import logo from '../assets/logo.png';

/**
 * @augments {React.Component<{
		m: any;
		propertyId: number;
		errors: any;
		image: string;
		imageMeta?: any;
		name: string;
		city: string;
		province: string;
	}>}
 */
export class Header extends React.Component {

	static propTypes = {
		m: PropTypes.any.isRequired,
		propertyId: PropTypes.number.isRequired,
		errors: PropTypes.any.isRequired,
		image: PropTypes.string,
		imageMeta: PropTypes.any,
		name: PropTypes.string.isRequired,
		city: PropTypes.string.isRequired,
		province: PropTypes.string.isRequired
	};

	image = null;

	getImageData() {
		return this.image.getData();
	}

	render() {
		const { m, propertyId, name, city, province, image, imageMeta, errors } = this.props;

		return (
			<div>
				<div className={ styles.content }>
					<div className={ styles.logo }>
						<img src={ logo } alt="" />
					</div>
					<div className={ styles.caption }>
						<h2 className={ styles.caption_header }>{ name }</h2>
						<span className={ styles.caption_underheader }>{ addr(city, province).join(', ') }</span>
					</div>
				</div>
				<div className={ styles.image }>
					<InlineImageEditor
						m={ m }
						propertyId={ propertyId }
						error={ !!errors['cover.image'] }
						ref={ (ref) => this.image = ref }
						image={ image }
						imageMeta={ imageMeta }
						height={ pdfPageHeight * 3 / 4 }
						width={ pdfPageWidth }
					/>
				</div>
			</div>
		);
	}
}
