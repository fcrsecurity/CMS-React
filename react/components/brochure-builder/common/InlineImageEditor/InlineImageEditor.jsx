// @ts-check
import React from 'react';
import PropTypes from 'prop-types';
import AvatarEditor from 'react-avatar-editor';
import cn from 'classnames';

import { assign } from '../../utils';

import { Spinner } from '../Spinner';
import { BrochureBuilder } from '../../BrochureBuilder';

// @ts-ignore
import styles from './InlineImageEditor.scss';

/**
 * @type {{
		m: any;
		propertyId: number;
		image?: string;
		imageMeta?: {
			scale?: number;
			x?: number;
			y?: number;
		};
		width: number;
		height: number;
		error?: boolean;
		maxScale?: number;
		minScale?: number;
		spec?: boolean;
	}}
 */
const props = null; // eslint-disable-line no-unused-vars

/**
 * @augments {React.PureComponent<typeof props, {
		loading: boolean;
		error: string;
		scale: number;
		position: {
			x: number;
			y: number;
		};
		props: typeof props;
	}>}
 */
export class InlineImageEditor extends React.PureComponent {

	mounted = false;
	editor = null;

	static propTypes = {
		m: PropTypes.any.isRequired,
		propertyId: PropTypes.number.isRequired,
		image: PropTypes.string,
		imageMeta: PropTypes.any,
		width: PropTypes.number.isRequired,
		height: PropTypes.number.isRequired,
		maxScale: PropTypes.number,
		minScale: PropTypes.number,
		error: PropTypes.bool,
		spec: PropTypes.bool,
	};

	static defaultProps = {
		maxScale: 4,
		minScale: 1,
		spec: false
	};

	state = {
		loading: true,
		error: '',
		scale: this.props.imageMeta && this.props.imageMeta.scale || 1,
		position: this.props.imageMeta ? {
			x: undefined === this.props.imageMeta.x ? 0 : this.props.imageMeta.x,
			y: undefined === this.props.imageMeta.y ? 1 : this.props.imageMeta.y,
		} : {x: 0, y: 1},
		props: this.props
	};

	componentDidMount() {
		this.mounted = true;
	}

	componentWillUnmount() {
		this.mounted = false;
	}

	setStateSafe(state, cb) {
		if (this.mounted) {
			this.setState(state, cb);
		}
	}

	allowManipulate = () => {
		return !(!this.state.props.image || this.state.loading || this.state.error);
	}

	handleWheel = (event) => {
		if (!this.allowManipulate()) {
			return;
		}

		event.preventDefault();

		const d = event.deltaY;
		let result = this.state.scale + 0.5 * (0 < d ? -1 : 1);

		result = this.props.maxScale < result ? this.props.maxScale : result;

		const { width, height } = this.editor.getCroppingRect();

		const minScale = this.props.spec && width < height ? Math.min(width / height, height / width) : this.props.minScale;
		result = minScale > result ? minScale : result;
		
		if (result !== this.state.scale) {
			this.setState({
				scale: result
			}, this.recalculate);
		}
	}

	calculateState = (scale, inputX, inputY, width, height) => {

		const rX = Math.abs(1 - width) / 2;
		const rY = Math.abs(1 - height) / 2;

		const x = Math.max(Math.min(inputX, 0.5 + rX), 0.5 - rX);
		const y = 1 < height && this.props.spec ? 0.5 - rY : Math.max(Math.min(inputY, 0.5 + rY), 0.5 - rY);

		return {
			scale,
			position: {
				x,
				y
			}
		};
	}

	handlePositionChange = ({ x, y }) => {
		if (!this.allowManipulate()) {
			return;
		}

		// @ts-ignore
		this.setState(({ scale }) => {
			const { width, height } = this.editor.getCroppingRect();
			return this.calculateState(scale, x, y, width, height);
		});
	}

	recalculate = () => {
		if (this.props.spec) {
			// @ts-ignore
			this.setState(({ position: { x, y }, scale }) => {
				const { width, height } = this.editor.getCroppingRect();
				return this.calculateState(scale, x, y, width, height);
			});
		}
	}

	setFile = (image) => {
		this.setStateSafe(({ props }) => ({
			loading: true,
			error: '',
			scale: 1,
			position: {x: 0, y: 1},
			props: assign(props, {
				image: ''
			})
		}), () => {
			this.setStateSafe(({ props }) => ({
				props: assign(props, {
					image: image + (-1 < image.indexOf('?') ? '&' : '?') + 'rnd=' + Math.random()
				})
			}));
		});
	}

	handleNewImageClick = () => {
		BrochureBuilder.openFileManager(this.props.propertyId, (file) => {
			if (file && file.url) {
				this.setFile(file.url);
			}
		});
	}

	handleLoadSuccess = () => {
		this.setStateSafe({
			loading: false,
			error: ''
		});
	}

	handleLoadFailure = () => {
		this.setStateSafe(({ props }) => ({
			loading: false,
			error: this.props.m.errors.failed_to_load_image,
			props: assign(props, {
				image: ''
			})
		}));
	}

	getData() {
		let crop = '';
		if (this.editor && this.state.props.image && !this.state.loading) {
			crop = this.editor.getImageScaledToCanvas().toDataURL();
		}

		return {
			crop,
			src: this.state.props.image || '',
			meta: {
				scale: this.state.scale,
				x: this.state.position.x,
				y: this.state.position.y,
			}
		};
	}

	render() {
		const { loading } = this.state;
		const { image, width, height } = this.state.props;
		return (
			<div
				className={
					cn(
						styles.content,
						{
							[styles.drag]: !!image,
							[styles.action]: !image,
							[styles.content_with_image]: !!image && !loading
						}
					)
				}
				style={{ width, height }} onWheel={ this.handleWheel }
			>
				{
					image ? (
						<div className={ styles.wrp }>
							<AvatarEditor
								ref={ (ref) => this.editor = ref }
								image={ image }
								width={ width }
								height={ height }
								scale={ this.state.scale }
								border={ 0 }
								borderRadius={ 0 }
								disableDrop={ true }
								position={ this.state.position }
								onLoadFailure={ this.handleLoadFailure }
								onLoadSuccess={ this.handleLoadSuccess }
								onPositionChange={ this.handlePositionChange }
							/>
							{
								loading && (
									<Spinner white={ this.props.spec } />
								)
							}
							<button className={ styles.add_button } onClick={ this.handleNewImageClick }>
								+
							</button>
						</div>
					) : (
						<div className={ styles.empty } onClick={ this.handleNewImageClick }>
							<div className={
								cn(
									styles.empty__border,
									{
										[styles.empty__border__error]: this.props.error || !!this.state.error
									}
								) 
							}>
								<div className={ styles.empty__action }>
									<div className={ styles.empty__plus }>+</div>
									<div className={ styles.empty__text }>{ this.props.m.buttons.add_an_image }</div>
									{
										this.state.error && (
											<div className={ styles.empty__error }>
												{ this.state.error }
											</div>
										)
									}
								</div>
							</div>
						</div>
					)
				}
			</div>
		);
	}
}
