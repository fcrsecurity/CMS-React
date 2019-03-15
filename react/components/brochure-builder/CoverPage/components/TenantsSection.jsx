// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
import cn from 'classnames';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

// @ts-ignore
import styles from './TenantsSection.scss';

/**
 * @augments {React.Component}
 */
export class TenantsSection extends React.Component {

	static propTypes = {
		onDragEnd: PropTypes.func.isRequired,
		children: PropTypes.node.isRequired
	};

	render() {
		return (
			<DragDropContext onDragEnd={ this.props.onDragEnd }>
				<Droppable droppableId="droppable" direction="horizontal">
					{
						(provided, snapshot) => {
							return (
								<div className={ cn(styles.content, { [styles.draggingOver]: snapshot.isDraggingOver }) } ref={ provided.innerRef }>
									<ul className={ styles.ul }>
										{ this.props.children }
									</ul>
								</div>
							);
						}
					}
				</Droppable>
			</DragDropContext>
		);
	}
}
