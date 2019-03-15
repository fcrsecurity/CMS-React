// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
import cn from 'classnames';

import { assign } from '../../utils';

// @ts-ignore
import styles from './Button.scss';

/**
 * @type {React.StatelessComponent<React.DetailedHTMLProps<React.ButtonHTMLAttributes<HTMLButtonElement>, HTMLButtonElement> & {
		mod?: 'reject'|'accept'|'edit'
	}>}
 */
export const Button = (props) => {
	const { mod } = props;

	const p = assign(props);
	delete p.mod;

	const className = cn(
		styles.button,
		props.className,
		{
			[styles.button__reject]: 'reject' === mod,
			[styles.button__accept]: 'accept' === mod,
			[styles.button__edit]: 'edit' === mod
		}
	);

	return (
		<button {...p} className={ className } />
	);
};

Button.propTypes = {
	mod: PropTypes.string,
	className: PropTypes.string
};
