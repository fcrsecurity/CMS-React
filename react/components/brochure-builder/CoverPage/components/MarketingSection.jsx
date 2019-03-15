// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
import cn from 'classnames';
import Textarea from 'react-textarea-autosize';

// @ts-ignore
import styles from './MarketingSection.scss';

import { CloseIcon, ReturnIcon } from '../../common/Icons';

/**
 * @augments {React.Component<{
		m: any;
		hideTagLine: boolean;
		description: string;
	}, {
		hideTagLine: boolean;
		description: string;
	}>}
 */
export class MarketingSection extends React.Component {

	static propTypes = {
		m: PropTypes.any.isRequired,
		hideTagLine: PropTypes.bool.isRequired,
		description: PropTypes.string.isRequired,
	};

	state = {
		hideTagLine: this.props.hideTagLine,
		description: this.props.description
	};

	getHideTagLine() {
		return this.state.hideTagLine;
	}

	getDescription() {
		return this.state.description;
	}

	handleDescriptionChange = (e) => {
		this.setState({
			description: e.target.value
		});
	}

	handleSloganToggle = () => {
		this.setState(({ hideTagLine }) => ({
			hideTagLine: !hideTagLine 
		}));
	}

	render() {
		const { m } = this.props;
		const { hideTagLine, description } = this.state;
		return (
			<div className={ styles.content }>
				<h3 className={ cn(styles.tagline) }>
					{
						!hideTagLine && m.interface.tagline
					}
					<button className={ styles.taglineToggler } onClick={ this.handleSloganToggle }>
						{ hideTagLine ? <ReturnIcon /> : <CloseIcon /> }
					</button>
				</h3>
				<Textarea
					className={ styles.description }
					value={ description }
					minRows={ 5 }
					maxRows={ 5 }
					onChange={ this.handleDescriptionChange }
				/>
			</div>
		);
	}
}
