// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
import cn from 'classnames';
import { Draggable } from 'react-beautiful-dnd';

// @ts-ignore
import styles from './TenantLogo.scss';

/**
 * @type {any}
 */
const DraggableAny = Draggable;

/**
 * @augments {React.Component<{
		image: string;
		index: number;
		title?: string;
	}>}
 */
export class TenantLogo extends React.Component {

	static propTypes = {
		image: PropTypes.string.isRequired,
		index: PropTypes.number.isRequired,
		title: PropTypes.string,
	};

	render() {
		const { image, index } = this.props;

		return (
			<DraggableAny key={ image } draggableId={ image } index={ index }>
				{
					(provided, snapshot) => {
						return (
							<span>
								<li 
									className={ cn(styles.content, { [styles.isDragging]: snapshot.isDragging }) }
									ref={ provided.innerRef }
									{...provided.draggableProps}
									{...provided.dragHandleProps}
									style={provided.draggableProps.style}
								>
									<img src={ image } alt='' />
								</li>
								{ provided.placeholder }
							</span>
						);
					}
				}
			</DraggableAny>
		);
	}
}
