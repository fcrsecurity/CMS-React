// @ts-check
import React from 'react';
import PropTypes from 'prop-types';

// @ts-ignore
import styles from './Errors.scss';

/**
 * @augments {React.Component<{
		m: any;
		errors: {
			[key: string]: string;
		};
 	}>}
 */
export class Errors extends React.Component {

	static propTypes = {
		m: PropTypes.any.isRequired,
		errors: PropTypes.any.isRequired
	};

	render() {
		const { m, errors } = this.props;

		const result = [];
		if (errors.domexception) {
			result.push(m.errors.domexception);
		}

		if (errors.exception) {
			result.push(m.errors.exception + errors.exception);
		}
		
		if (errors.plans) {
			result.push(m.errors.plans_required);
		}

		if (errors['cover.description']) {
			result.push(m.errors.description_required);
		}

		if (errors['cover.image'] ||
			errors['contact.image'] ||
			errors['contact.lifestyle'] ||
			errors['demographic.image']
		) {
			result.push(m.errors.images_required);
		}

		return (
			<div className={ styles.content }>
				{
					result.length ? (
						<div>
							<div>
								{ m.labels.errors_before_save }:
							</div>
							<ul>
								{
									result.map((item, index) =>
										<li key={ index }>{ item }</li>
									)
								}
							</ul>
						</div>
					) : (
						<div>{ m.errors.something_went_wrong }</div>
					)
				}
			</div>
		);
	}
}
