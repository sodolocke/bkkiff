/*global wp*/
const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const {
	PlainText,
	RichText,
	BlockControls,
	AlignmentToolbar,
	InspectorControls,
	ColorPalette,
	MediaUpload,
	InnerBlocks,
} = wp.blockEditor;
const {
	Button,
	TextControl,
	TextareaControl,
	PanelBody,
	Placeholder,
	QueryControls,
	RangeControl,
	Spinner,
	ToggleControl,
	Toolbar,
	ToolbarGroup,
	ToolbarButton,
	SelectControl,
} = wp.components;
const {
	withState,
	withInstanceId,
} = wp.compose;

const { withSelect } = wp.data;
const __ = wp.i18n.__; // The __() for internationalization.

const {__experimentalLinkControl } = wp.blockEditor;
const LinkControl = __experimentalLinkControl;

registerBlockType( 'n4d/carousel', {
	title: 'N4D Carousel',
	icon: 'slides',
	category: 'n4d-blocks',
	attributes: {
		columns: {
			type: 'number',
		},
		id: {
			type: 'number',
		},
		ids: {
			type: 'array',
			default: [],
		},
		thumbnails: {
			type: 'array',
			default: []
		},
		autoPlay: {
			type: 'boolean',
			default: false
		},
		fill: {
			type: 'boolean',
			default: false
		},
		spill: {
			type: 'boolean',
			default: false
		},
		indicators: {
			type: 'boolean',
			default: false
		},
		indicatorsThumbnails: {
			type: 'boolean',
			default: false
		},
		indicatorsThumbnailsContain: {
			type: 'boolean',
			default: false
		},
		ratio4x3: {
			type: 'boolean',
			default: false
		},
		cover: {
			type: 'boolean',
			default: false
		},
		modal: {
			type: 'boolean',
			default: false
		},
	},
	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { columns, id, ids,thumbnails, spill, fill, autoPlay, indicators, indicatorsThumbnails, indicatorsThumbnailsContain, ratio4x3, cover, modal } = attributes;
		const onChangeColumns = value => {
			setAttributes( { columns: value } );
		};
		if (columns == undefined) setAttributes( { columns: 5 } );

		const button_class = "components-button is-secondary";
		const ALLOWED_MEDIA_TYPES = [ 'image' ];

		setAttributes( { id: instanceId } );

		let preview_id = "n4d-gallery-preview-"+id;

		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<h3>Carousel</h3>
					<ToggleControl
						label={ __( 'Indicators' ) }
						checked={ indicators }
						onChange= {(value) => {
							setAttributes( { indicators: value } );
						}}
					/>
					{indicators &&
						<ToggleControl
							label={ __( 'Thumbnails' ) }
							checked={ indicatorsThumbnails }
							onChange= {(value) => {
								setAttributes( { indicatorsThumbnails: value } );
							}}
						/>
					}
					{indicators &&
						<ToggleControl
							label={ __( 'Thumbnails Contain' ) }
							checked={ indicatorsThumbnailsContain }
							onChange= {(value) => {
								setAttributes( { indicatorsThumbnailsContain: value } );
							}}
						/>
					}
					<ToggleControl
						label={ __( 'AutoPlay' ) }
						checked={ autoPlay }
						onChange= {(value) => {
							setAttributes( { autoPlay: value } );
						}}
					/>
					<hr />
					<h3>Image</h3>
					<ToggleControl
						label={ __( '4x3 Ratio' ) }
						checked={ ratio4x3 }
						onChange= {(value) => {
							setAttributes( { ratio4x3: value } );
						}}
					/>
					<ToggleControl
						label={ __( 'Cover Image' ) }
						checked={ cover }
						onChange= {(value) => {
							setAttributes( { cover: value } );
						}}
					/>
					<hr />
					<ToggleControl
						label={ __( 'Fill' ) }
						checked={ fill }
						onChange= {(value) => {
							setAttributes( { fill: value } );
						}}
					/>
					<ToggleControl
						label={ __( 'Spill' ) }
						checked={ spill }
						onChange= {(value) => {
							setAttributes( { spill: value } );
						}}
					/>
					<hr />
					<h3>Modal</h3>
					<ToggleControl
						label={ __( 'Modal' ) }
						checked={ modal }
						onChange= {(value) => {
							setAttributes( { modal: value } );
						}}
					/>
				</PanelBody>
			</InspectorControls>,
			<div class="n4d-gallery">

				<div class="components-placeholder__label">
					<span class="block-editor-block-icon">
						<svg aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" class="dashicon dashicons-slides"><path d="M5 14V6h10v8H5zm-3-1V7h2v6H2zm4-6v6h8V7H6zm10 0h2v6h-2V7zm-3 2V8H7v1h6zm0 3v-2H7v2h6z"></path></svg>
					</span>N4D Carousel
				</div>

				<div class="components-placeholder__fieldset">
					<MediaUpload
						onSelect={(media) => {
							let urls = [];
							let new_ids = media.map((item) => {
								urls.push(item.sizes.full.url);
								return item.id;
							});
							setAttributes( { ids: new_ids });
							setAttributes( { thumbnails: urls });
						}}
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						value={ ids }
						multiple={true}
						gallery={true}
						render={ ({open}) => {
							if (ids){
								return <button
									type="button"
									className={button_class}
									onClick={open}>
									Modify Carousel
								</button>
							} else {
								return <button type="button" className={button_class} onClick={open}>Create Carousel</button>;
							}
						}}
					/>
				</div>
				<div id={preview_id} class="n4d-gallery-preview">
				{thumbnails.map((url, index) => {
					let col_style = {
						backgroundImage: 'url(' + url + ')'
					};
					return ([
						<div className={"item"} key={index} style={col_style}></div>
					])
				})}
				</div>
			</div>
		]);
	}),

	save() {
		// Rendering in PHP
		return null;
	},
} );
registerBlockType( 'n4d/container', {
	title: 'N4D container',
	icon: 'editor-table',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		contain : {
			type: 'boolean'
		},
		overflow : {
			type: 'boolean'
		},
		bg: {
			type: 'string'
		},
		bgColor: {
			type: 'string',
			default: 'transparent'
		},
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, contain, overflow, bg, bgColor } = attributes;
		const wrapClass = "n4d-section n4d-container";
		const updateAnchor = value => {
			setAttributes( { anchor: value } );
		};
		const toggleContain = value => {
			setAttributes( { contain: value } );
		}
		const toggleOverflow = value => {
			setAttributes( { overflow: value } );
		}
		const ALLOWED_MEDIA_TYPES = [ 'image' ];
		const button_class = "components-button is-secondary";

		const removeMedia = () => {
			setAttributes({ bg: undefined });
		}

		setAttributes( { id: instanceId } );

		var boxStyle = {
			backgroundColor: bgColor
		};


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Section Anchor' ) }
						value={ anchor }
						onChange={ updateAnchor }
					/>
					<ToggleControl
						label={ __( 'Fill Width' ) }
						checked={ contain }
						onChange={ toggleContain }
					/>
					<ToggleControl
						label={ __( 'Overflow Container' ) }
						checked={ overflow }
						onChange={ toggleOverflow }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Background' ) }>
					<div className="mediaUpload-wrapper components-base-control">
						{ bg ? <img src={bg} /> : "" }
						<MediaUpload
							onSelect={(media) => {
								setAttributes( { bg: media.sizes.full.url } );
							}}
							allowedTypes={ ALLOWED_MEDIA_TYPES }
							value={ bg }
							render={ ({open}) => {
								if (bg){
									return <button
										type="button"
										className={button_class}
										onClick={removeMedia}>
										Change Background
									</button>
								} else {
									return <button type="button" className={button_class} onClick={open}>Select Background</button>;
								}

							}}
						/>
						<h4>Background Color</h4>
						<ColorPalette
							value={ bgColor }
							onChange={value => {
								setAttributes( { bgColor: value } );
							}}
						/>
					</div>
				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container" style={boxStyle}>
				<InnerBlocks />
			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				contain,
				overflow,
				bg,
				bgColor
			}
		} = props;

		let section_id      = (anchor) ? anchor : "section-"+(id + 1);
		let section_class   = (overflow) ? "n4d-overflow" : "";
		let row_style       = {};
		if (bg) row_style["backgroundImage"] = 'url(' + bg + ')';
		let container_class = (contain) ? "container-fluid" : "container";
		if (bgColor) row_style["backgroundColor"] = bgColor;

		return (
			<div id={section_id} className={section_class} data-index={id} style={row_style}>
				<div className={container_class}>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},
} );
registerBlockType( 'n4d/row', {
	title: 'N4D Row',
	icon: 'table-row-after',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		fillWidth : {
			type: 'boolean'
		},
		fillHeight : {
			type: 'boolean',
			default: false
		},
		bg: {
			type: 'string'
		},
		bgColor: {
			type: 'string',
			default: 'transparent'
		},
		gutter: {
			type: 'number',
			default: 4
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, fillWidth, bg, bgColor, gutter, fillHeight } = attributes;
		const wrapClass = "n4d-section n4d-row";
		const updateAnchor = value => {
			setAttributes( { anchor: value } );
		};
		const ALLOWED_MEDIA_TYPES = [ 'image' ];
		const button_class = "components-button is-secondary";

		const removeMedia = () => {
			setAttributes({ bg: undefined });
		}

		setAttributes( { id: instanceId } );

		var boxStyle = {
			backgroundColor: bgColor
		};


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Section Anchor' ) }
						value={ anchor }
						onChange={ updateAnchor }
					/>
					<ToggleControl
						label={ __( 'Fill Width' ) }
						checked={ fillWidth }
						onChange={ (value) => setAttributes( { fillWidth: value } ) }
					/>
					<ToggleControl
						label={ __( 'Fill Height' ) }
						checked={ fillHeight }
						onChange={ (value) => setAttributes( { fillHeight: value } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Background' ) }>
					<div className="mediaUpload-wrapper components-base-control">
						{ bg ? <img src={bg} /> : "" }
						<MediaUpload
							onSelect={(media) => {
								setAttributes( { bg: media.sizes.full.url } );
							}}
							allowedTypes={ ALLOWED_MEDIA_TYPES }
							value={ bg }
							render={ ({open}) => {
								if (bg){
									return <button
										type="button"
										className={button_class}
										onClick={removeMedia}>
										Change Background
									</button>
								} else {
									return <button type="button" className={button_class} onClick={open}>Select Background</button>;
								}

							}}
						/>
						<h4>Background Color</h4>
						<ColorPalette
							value={ bgColor }
							onChange={value => {
								setAttributes( { bgColor: value } );
							}}
						/>
						<hr />
						<TextControl
							label={ __( 'Gutter' ) }
							type="number"
							max="5"
							min="0"
							value={ gutter }
							onChange={(value) => setAttributes( { gutter: Number(value) } ) }
						/>
					</div>
				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container" style={boxStyle}>
				<InnerBlocks />
			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				bg,
				bgColor,
				gutter,
				fillHeight
			}
		} = props;

		let section_id = (anchor) ? anchor : "section-"+(id + 1);


		let section_class = "";
		let row_style = {};
		if (bg) {
			row_style["backgroundImage"] = 'url(' + bg + ')';
		}
		if (bgColor) {
			row_style["backgroundColor"] = bgColor;
		}
		let row_class = "row"
		if ((gutter && gutter !== 4) || gutter == 0) row_class += ` g-${gutter}`;

		if (fillHeight){
//			section_class += " h-100";
			row_class += " h-100";
		}


		return (
			<div id={section_id} className={section_class} data-index={id}>
				<div className={row_class} style={row_style}>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},

} );
registerBlockType( 'n4d/col', {
	title: 'N4D Column',
	icon: 'table-col-after',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		xs: {
			type: 'number'
		},
		sm: {
			type: 'number'
		},
		md: {
			type: 'number'
		},
		lg: {
			type: 'number'
		},
		xl: {
			type: 'number'
		},
		offset_xs: {
			type: 'number'
		},
		offset_sm: {
			type: 'number'
		},
		offset_md: {
			type: 'number'
		},
		offset_lg: {
			type: 'number'
		},
		offset_xl: {
			type: 'number'
		},
		bg: {
			type: 'string'
		},
		bgColor: {
			type: 'string',
			default: ''
		},
		fillHeight: {
			type: 'boolean'
		},
		centerVertical: {
			type: 'boolean'
		},
		spaceBetween: {
			type: 'boolean'
		},
		padding: {
			type: 'number',
			default: 0
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { xs, sm, md, lg, xl, offset_xs, offset_sm, offset_md, offset_lg, offset_xl, bg, bgColor, fillHeight, padding, centerVertical, spaceBetween } = attributes;
		const wrapClass = "n4d-n4d-col";

		setAttributes( { id: instanceId } );

		const ALLOWED_MEDIA_TYPES = [ 'image' ];
		const button_class = "components-button is-secondary";

		const removeMedia = () => {
			setAttributes({ bg: undefined });
		}
		const colStyle = {
			backgroundImage: 'url('+bg+')',
			backgroundSize: 'cover',
			backgroundColor: bgColor
		}


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Padding' ) }
						type="number"
						max="5"
						min="0"
						value={ padding }
						onChange={(value) => setAttributes( { padding: Number(value) } )}
					/>
					<ToggleControl
						label={ __( 'Fill Height' ) }
						checked={ fillHeight }
						onChange={ value => setAttributes( { fillHeight: value } ) }
					/>
					<ToggleControl
						label={ __( 'Center Content Vertically' ) }
						checked={ centerVertical }
						onChange={ value => setAttributes( { centerVertical: value } ) }
					/>
					<ToggleControl
						label={ __( 'Space Content Top & Bottom' ) }
						checked={ spaceBetween }
						onChange={ value => setAttributes( { spaceBetween: value } ) }
					/>



					<hr />
					<h4>Columns</h4>
					<TextControl
						label={ __( 'XS' ) }
						type="number"
						max="12"
						min="0"
						value={ xs }
						onChange={(value) => {
							setAttributes( { xs: Number(value) } );
						}}
					/>
					<TextControl
						label={ __( 'SM' ) }
						type="number"
						max="12"
						min="0"
						value={ sm }
						onChange={(value) => {
							setAttributes( { sm: Number(value) } );
						}}
					/>
					<TextControl
						label={ __( 'MD' ) }
						type="number"
						max="12"
						min="0"
						value={ md }
						onChange={(value) => {
							setAttributes( { md: Number(value) } );
						}}
					/>
					<TextControl
						label={ __( 'LG' ) }
						type="number"
						max="12"
						min="0"
						value={ lg }
						onChange={(value) => {
							setAttributes( { lg: Number(value) } );
						}}
					/>
					<TextControl
						label={ __( 'XL' ) }
						type="number"
						max="12"
						min="0"
						value={ xl }
						onChange={(value) => {
							setAttributes( { xl: Number(value) } );
						}}
					/>
					<hr />
					<TextControl
						label={ __( 'Offset XS' ) }
						type="number"
						max="12"
						min="0"
						value={ offset_xs }
						onChange={(value) => {
							setAttributes( { offset_xs: Number(value) } );
						}}
					/>
					<TextControl
						label={ __( 'Offset SM' ) }
						type="number"
						max="12"
						min="0"
						value={ offset_sm }
						onChange={(value) => {
							setAttributes( { offset_sm: Number(value) } );
						}}
					/>
					<TextControl
						label={ __( 'Offset MD' ) }
						type="number"
						max="12"
						min="0"
						value={ offset_md }
						onChange={(value) => {
							setAttributes( { offset_md: Number(value) } );
						}}
					/>
					<TextControl
						label={ __( 'Offset LG' ) }
						type="number"
						max="12"
						min="0"
						value={ offset_lg }
						onChange={(value) => {
							setAttributes( { offset_lg: Number(value) } );
						}}
					/>
					<TextControl
						label={ __( 'Offset XL' ) }
						type="number"
						max="12"
						min="0"
						value={ offset_xl }
						onChange={(value) => {
							setAttributes( { offset_xl: Number(value) } );
						}}
					/>
				</PanelBody>
				<PanelBody title={ __( 'Background' ) }>
					<div className="mediaUpload-wrapper components-base-control">
						{ bg ? <img src={bg} /> : "" }
						<MediaUpload
							onSelect={(media) => {
								setAttributes( { bg: media.sizes.full.url } );
							}}
							allowedTypes={ ALLOWED_MEDIA_TYPES }
							value={ bg }
							render={ ({open}) => {
								if (bg){
									return <button
										type="button"
										className={button_class}
										onClick={removeMedia}>
										Change Background
									</button>
								} else {
									return <button type="button" className={button_class} onClick={open}>Select Background</button>;
								}

							}}
						/>
					</div>
					<h4>Background Color</h4>
					<ColorPalette
						value={ bgColor }
						onChange={value => {
							setAttributes( { bgColor: value } );
						}}
					/>
				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container" style={colStyle}>
				<InnerBlocks />
			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id, xs, sm, md, lg, xl, offset_xs, offset_sm, offset_md, offset_lg, offset_xl, bg, bgColor, fillHeight, padding, centerVertical, spaceBetween
			}
		} = props;


		let section_class = (xs) ? "col-"+xs : "col";
		if (sm) {
			section_class +=  " col-sm-"+sm;
		} else if (!sm && xs) {
			section_class += " col-sm";
		}
		if (md) {
			section_class += " col-md-"+md;
		} else if (!md && (xs || sm)) {
			section_class += " col-md";
		}
		if (lg) section_class += " col-lg-"+lg;
		if (xl) section_class += " col-xl-"+xl;

		if (offset_xs !== undefined) section_class += " offset-"+offset_xs;
		if (offset_sm) section_class += " offset-sm-"+offset_sm;
		if (offset_md) section_class += " offset-md-"+offset_md;
		if (offset_lg) section_class += " asdf offset-lg-"+offset_lg;
		if (offset_xl) section_class += " offset-xl-"+offset_xl;

		let colStyle = {};
		let boxStyle = {};

		if (bg){
			colStyle.backgroundImage = 'url('+bg+')';
		}
		if (bgColor){
			boxStyle.backgroundColor = bgColor;
		}
		let box_class = "col-wrap";
		if (fillHeight){
			box_class += " h-100";
		}
		if (spaceBetween) {
			box_class += " spaceBetween";
		}
		if (centerVertical) {
			box_class += " v-center";
		}
		if (padding && padding !== 0){
			box_class += " p-"+padding;
		}



		return (
			<div className={section_class} data-index={id} style={colStyle}>
				<div className={box_class} style={boxStyle}>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},

} );
registerBlockType( 'n4d/well', {
	title: 'N4D Well',
	icon: <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24" role="img" aria-hidden="true" focusable="false"><path fill="#CCC" d="M512 512H0V0h512v512z"></path></svg>,
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		fullHeight: {
			type: 'boolean'
		},
		spaceBetween: {
			type: 'boolean'
		},
		bg: {
			type: 'string'
		},
		bgColor: {
			type: 'string',
			default: 'transparent'
		},
		padding: {
			type: 'number',
			default: 4
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, fullHeight, bg, bgColor, padding, spaceBetween } = attributes;
		const wrapClass = "n4d-well";

		setAttributes( { id: instanceId } );


		const ALLOWED_MEDIA_TYPES = [ 'image' ];
		const TEMPLATE = [
			[ 'core/paragraph', { placeholder: ' ' } ],
		];
		const button_class = "components-button is-secondary";

		const boxStyle = {
			backgroundImage: 'url('+bg+')',
			backgroundSize: 'cover',
			backgroundColor: bgColor
		}
		if (padding){
			boxStyle.padding = (padding/2.5)+"rem";
		} else {
			boxStyle.padding = 0;
		}


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Well Anchor' ) }
						value={ anchor }
						onChange={ (value) => setAttributes( { anchor: value } ) }
					/>
					<TextControl
						label={ __( 'Padding' ) }
						type="number"
						max="5"
						min="0"
						value={ padding }
						onChange={(value) => setAttributes( { padding: Number(value) } )}
					/>
					<ToggleControl
						label={ __( 'Fill Height' ) }
						checked={ fullHeight }
						onChange={(value) => setAttributes( { fullHeight: value } )}
					/>
					<ToggleControl
						label={ __( 'Space Between' ) }
						checked={ spaceBetween }
						onChange={(value) => setAttributes( { spaceBetween: value } )}
					/>



				</PanelBody>
				<PanelBody title={ __( 'Background' ) }>
					<div className="mediaUpload-wrapper components-base-control">
						{ bg ? <img src={bg} /> : "" }
						<MediaUpload
							onSelect={(media) => {
								setAttributes( { bg: media.sizes.full.url } );
							}}
							allowedTypes={ ALLOWED_MEDIA_TYPES }
							value={ bg }
							render={ ({open}) => {
								if (bg){
									return <button
										type="button"
										className={button_class}
										onClick={() => setAttributes({ bg: undefined })}
									>
										Change Background
									</button>
								} else {
									return <button type="button" className={button_class} onClick={open}>Select Background</button>;
								}

							}}
						/>
					</div>
					<h4>Background Color</h4>
					<ColorPalette
						value={ bgColor }
						onChange={value => {
							setAttributes( { bgColor: value } );
						}}
					/>
				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container" style={boxStyle}>
				<InnerBlocks template={ TEMPLATE } />
			</div>
		]);
	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				fullHeight,
				bg,
				bgColor,
				padding,
				spaceBetween
			}
		} = props;

		let section_id = (anchor) ? anchor : "well-"+(id + 1);
		let section_class = "well";
		if (fullHeight){
			section_class += " h-100";
		}
		if (spaceBetween){
			section_class += " spaceBetween";
		}

		let boxStyle = {};

		if (bg){
			boxStyle.backgroundImage = 'url('+bg+')';
		}
		if (bgColor){
			boxStyle.backgroundColor = bgColor;
		}

		if (padding && padding !== 4){
			section_class += " p-"+padding;
		}

		return (

			<div id={section_id} className={section_class} data-index={id} style={boxStyle}>
				<InnerBlocks.Content />
			</div>

		);
	},

} );
registerBlockType( 'n4d/collapse', {
	title: 'N4D Collapse',
	icon: 'arrow-down-alt2',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		title: {
			type: 'string',
		},
		accordian: {
			type: 'boolean',
			default: false
		},
		accordianAnchor: {
			type: 'string',
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, title, accordian, accordianAnchor } = attributes;
		const wrapClass = "n4d-collapse";
		const updateAnchor = value => {
			setAttributes( { anchor: value } );
		};
		const onChangeTitle = value => {
			setAttributes( { title: value } );
		}

		setAttributes( { id: instanceId } );


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ updateAnchor }
					/>
					<ToggleControl
						label={ __( 'Accordian Mode' ) }
						checked={ accordian }
						onChange={ (value) => setAttributes( { accordian: value } ) }
					/>

					<TextControl
						label={ __( 'Accordian Parent Anchor' ) }
						value={ accordianAnchor }
						onChange={ (value) => setAttributes( { accordianAnchor: value } ) }
					/>
				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container">
				<RichText
					tagName="h3"
					placeholder={__( 'Title' )}
					value={title}
					onChange={onChangeTitle}
				/>
				<div className={"collapse"}>
					<InnerBlocks />
				</div>
			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				title,
				accordian,
				accordianAnchor
			}
		} = props;

		let section_id = (anchor) ? anchor : "n4d-collapse-"+(id + 1);
		let section_class = (accordian) ? "accordion-item" : "n4d-collapse";

		return (
			<div id={section_id} className={section_class} data-index={id}>

				{!accordian &&
						[<h4 className="collapse-title"><a className="trigger" data-bs-toggle="collapse" href={"#collapse-"+section_id} role="button" aria-expanded="false" aria-controls={section_id}>
						{title}
						</a>
						</h4>,
						<div id={"collapse-"+section_id} class="collapse">
							<div class="collapse-wrap">
							<InnerBlocks.Content />
							</div>
						</div>]
				}
				{accordian &&
					[
						<h4 className="accordion-header">
							<a class="trigger" data-bs-toggle="collapse" href={"#"+section_id} role="button" aria-expanded="false" aria-controls={section_id}>{title}</a>
						</h4>,
						<div id={"collapse-"+section_id} class="accordion-collapse collapse" data-bs-parent={"#"+accordianAnchor}>
							<div class="collapse-wrap">
							<InnerBlocks.Content />
							</div>
						</div>
					]
				}
			</div>
		);
	},

} );
registerBlockType( 'n4d/accordion', {
	title: 'N4D Accordion',
	icon: 'arrow-down-alt2',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		title: {
			type: 'string',
		},
		items: {
			type: 'array',
			default: []
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, overflow, items, scrollBottom, modal } = attributes;
		const ALLOWED_MEDIA_TYPES = [ 'image', 'video' ];
		const button_class = "components-button is-secondary changeBG";
		setAttributes( { id: instanceId } );

		const setItems = () => {
			const value = items.map((item, i) => {
				item.id    = i + 1;
				item.order = i;
				return item
			})
			setAttributes({ items: value})
		}
		const deleteItem = (n) => {
			let c = 0;
			const value = items.map((item, i) => {
				if (n !== i){
					c++
					item.id = c;
					return item
				}
			})
			setAttributes({ items: value.filter(function( el ) {
			   return el !== undefined;
			})})
		}

		function array_move(arr, old_index, new_index) {
			if (new_index >= arr.length) {
				var k = new_index - arr.length + 1;
				while (k--) {
					arr.push(undefined);
				}
			}
			arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);

			setAttributes({ items: arr})

			setItems()

			return arr;
		};


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ value => {
							setAttributes( { anchor: value } )
						}}
					/>
					<TextControl
						label={ __( 'Modal Target' ) }
						value={ modal }
						onChange={ value => {
							setAttributes( { modal: value } )
						}}
					/>
					<ToggleControl
						label={ __( 'Overflow Container' ) }
						checked={ overflow }
						onChange={ value => {
							setAttributes( { overflow: value } );
						}}
					/>
				</PanelBody>
			</InspectorControls>,
			<div class="n4d-accordion">
				<div class="component-header">
					<span class="component-icon">
						<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 500 500">
						  <path d="M500,124.48v249.4H1.2V124.48h498.8ZM468.83,218H32.38v124.7h436.45v-124.7Z"/>
						  <rect x="1.2" y="-.22" width="498.8" height="93.52"/>
						  <rect x="1.2" y="405.05" width="498.8" height="93.53"/>
						</svg>
						N4D accordion
					</span>


					<button
						className="components-button add-nav"
						onClick={ () => {
							items.forEach((item, i) => {
								items[i].show = "";
							})

							items.push({
								id: items.length + 1,
								title: "",
								content: "",
								url: "",
								img_id: "",
								show: "",
								right: false,
								modal: false,
								link: "",
								link_url: "",
								pid: false,
								mobile_id: false,
								mobile_url: false,
							});

							setItems()
						}}
					>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
							<path d="M18 11.2h-5.2V6h-1.6v5.2H6v1.6h5.2V18h1.6v-5.2H18z"></path>
						</svg>
					</button>
				</div>
				<div class="component-body">
				{items.map( (item, i) => {
					const divStyle = {
					//	backgroundImage: 'url('+item.url+')',
					}

					if (item.show == undefined) item.show = ''

					const itemClass = (item.right) ? "item right" : "item"
					const contentClass = (item.right) ? "content right" : "content"

					return (
						<div className={itemClass} style={divStyle}>
							<button
								type="button"
								className="components-button has-icon close-nav"
								aria-label="Remove Nav"
								onClick={()=>{
									deleteItem(i)
								}}
							>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path>
								</svg>
							</button>
							<div class="slide-control">
								<a
									class="btn"
									onClick={ () => {
										if (i > 0) array_move(items, i, i - 1)
									}}
								>
								<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"></path></svg>
								</a>
								<a
									class="btn"
									onClick={ () => {
										if (i < (items.length - 1)) array_move(items, i, i + 1)
									}}
								>
								<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>
								</a>
							</div>

							<div className="images">
								<div className="image">
									{item.url &&
										<img src={item.url} className="main" />
									}
									<MediaUpload
										onSelect={(media) => {
											items[i].url = media.url
											items[i].img_id = media.id
											setItems()
										}}
										allowedTypes={ ALLOWED_MEDIA_TYPES }
										value={ item.img_id }
										multiple={false}
										gallery={false}
										render={ ({open}) => {
											if (item.img_id){
												return <button
													type="button"
													className={button_class}
													onClick={open}>
													Change Background
												</button>
											} else {
												return <button type="button" className={button_class} onClick={open}>Add Background</button>;
											}
										}}
									/>
								</div>
								<div className="image">
									{item.mobile_url &&
										<img src={item.mobile_url} className="mobile" />
									}
									<MediaUpload
										onSelect={(media) => {
											items[i].mobile_url = media.url
											items[i].mobile_id = media.id
											setItems()
										}}
										allowedTypes={ ALLOWED_MEDIA_TYPES }
										value={ item.mobile_id }
										multiple={false}
										gallery={false}
										render={ ({open}) => {
											if (item.mobile_id){
												return <button
													type="button"
													className={button_class}
													onClick={open}>
													Change Mobile Image
												</button>
											} else {
												return <button type="button" className={button_class} onClick={open}>Add Mobile Image</button>;
											}
										}}
									/>
								</div>
							</div>



							<div className={contentClass}>



								<div className="text-wrap">
									<RichText
										tagName="p"
										placeholder={__( 'Title' )}
										value={ item.title }
										onChange={ value => {
											items[i].title = value
											setItems();
										}}
										className="title"
									/>
								</div>

								<div className="text-wrap textarea">
									<RichText
										tagName="p"
										placeholder={__( 'Description' )}
										value={ item.content }
										onChange={ value => {
											items[i].content = value
											setItems();
										}}
										className="text-content"
									/>
								</div>



							</div>
						</div>
					)
				})}
				</div>

			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				items,
				overflow,
			}
		} = props;

		let section_id = (anchor) ? anchor : "n4d-accordion-"+(id + 1);
		let section_class = "accordion accordion-flush horizontal";
		if (overflow) section_class += " overflow";

		let slides = (items.length > 0) ? items.map( (value, index) => {
			const active    = (index == 0) ? "active" : ""
			let content     = value.content.split('\n').join("<br />")
			let expanded    = (index == 0) ? " expand" : ""
			let is_expanded = (index == 0) ? "true" : "false"
			let show        = (index == 0) ? " show" : ""
			let slides = (<div className={"accordion-item"+expanded}>
				<h2 class="accordion-header">
					<button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target={"#n4d-accordion-"+id+"-"+index} aria-expanded={is_expanded} aria-controls={"n4d-accordion-"+id+"-"+index}>{index+1} <span>{value.title}</span></button>
				</h2>
				<div id={"n4d-accordion-"+id+"-"+index} className={"accordion-collapse collapse collapse-horizontal"+show} data-bs-parent={"#"+section_id}>
					<div class="accordion-body" style={"background-image: url("+value.url+");"}>
					{(content.length > 0) &&
						<div class="content" dangerouslySetInnerHTML={{__html:content}}></div>
					}
					</div>
				</div>
			</div>)

			return slides;
		}) : ""





		return (
			<div id={section_id} className={section_class} data-index={id}>{slides}</div>
		);
	},

} );
registerBlockType( 'n4d/marquee', {
	title: 'N4D Marquee',
	icon: 'embed-generic',
	category: 'n4d-blocks',
	attributes: {
		id: {
			type: 'number'
		},
		title: {
			type: 'string'
		},
		content: {
			type: 'string'
		},
		anchor: {
			type: 'string'
		},
		modal: {
			type: 'string',
			default: '#preview-modal'
		},
		overflow: {
			type: 'Boolean',
		},
		scrollBottom : {
			type: 'Boolean',
		},
		ids: {
			type: 'array',
		},
		items: {
			type: 'array',
			default: []
		}

	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, overflow, items, scrollBottom, modal } = attributes;
		const ALLOWED_MEDIA_TYPES = [ 'image', 'video' ];
		const button_class = "components-button is-secondary changeBG";
		setAttributes( { id: instanceId } );

		const setItems = () => {
			const value = items.map((item, i) => {
				item.id    = i + 1;
				item.order = i;
				return item
			})
			setAttributes({ items: value})
		}
		const deleteItem = (n) => {
			let c = 0;
			const value = items.map((item, i) => {
				if (n !== i){
					c++
					item.id = c;
					return item
				}
			})
			setAttributes({ items: value.filter(function( el ) {
			   return el !== undefined;
			})})
		}

		function array_move(arr, old_index, new_index) {
			if (new_index >= arr.length) {
				var k = new_index - arr.length + 1;
				while (k--) {
					arr.push(undefined);
				}
			}
			arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);

			setAttributes({ items: arr})

			setItems()

			return arr;
		};


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ value => {
							setAttributes( { anchor: value } )
						}}
					/>
					<TextControl
						label={ __( 'Modal Target' ) }
						value={ modal }
						onChange={ value => {
							setAttributes( { modal: value } )
						}}
					/>
					<ToggleControl
						label={ __( 'Overflow Container' ) }
						checked={ overflow }
						onChange={ value => {
							setAttributes( { overflow: value } );
						}}
					/>
					<ToggleControl
						label={ __( 'Scroll to Bottom' ) }
						checked={ scrollBottom }
						onChange={ value => {
							setAttributes( { scrollBottom: value } );
						}}
					/>
				</PanelBody>
			</InspectorControls>,
			<div class="n4d-marquee">
				<div class="component-header">
					<span class="component-icon">
						<svg aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" class="dashicon dashicons-format-gallery">
						<path fill="currentColor" d="M17 4H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-3 6.5L12.5 12l1.5 1.5V15l-3-3l3-3v1.5zm1 4.5v-1.5l1.5-1.5l-1.5-1.5V9l3 3l-3 3z"/>
						</svg>
						N4D Marquee
					</span>


					<button
						className="components-button add-nav"
						onClick={ () => {
							items.forEach((item, i) => {
								items[i].show = "";
							})

							items.push({
								id: items.length + 1,
								title: "",
								content: "",
								url: "",
								img_id: "",
								show: "",
								right: false,
								modal: false,
								link: "",
								link_url: "",
								pid: false,
								mobile_id: false,
								mobile_url: false,
							});

							setItems()
						}}
					>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
							<path d="M18 11.2h-5.2V6h-1.6v5.2H6v1.6h5.2V18h1.6v-5.2H18z"></path>
						</svg>
					</button>
				</div>
				<div class="component-body">
				{items.map( (item, i) => {
					const divStyle = {
					//	backgroundImage: 'url('+item.url+')',
					}

					if (item.show == undefined) item.show = ''

					const itemClass = (item.right) ? "item right" : "item"
					const contentClass = (item.right) ? "content right" : "content"

					return (
						<div className={itemClass} style={divStyle}>
							<button
								type="button"
								className="components-button has-icon close-nav"
								aria-label="Remove Nav"
								onClick={()=>{
									deleteItem(i)
								}}
							>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path>
								</svg>
							</button>
							<div class="slide-control">
								<a
									class="btn"
									onClick={ () => {
										if (i > 0) array_move(items, i, i - 1)
									}}
								>
								<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"></path></svg>
								</a>
								<a
									class="btn"
									onClick={ () => {
										if (i < (items.length - 1)) array_move(items, i, i + 1)
									}}
								>
								<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>
								</a>
							</div>

							<div className="images">
								<div className="image">
									{item.url &&
										<img src={item.url} className="main" />
									}
									<MediaUpload
										onSelect={(media) => {
											items[i].url = media.url
											items[i].img_id = media.id
											setItems()
										}}
										allowedTypes={ ALLOWED_MEDIA_TYPES }
										value={ item.img_id }
										multiple={false}
										gallery={false}
										render={ ({open}) => {
											if (item.img_id){
												return <button
													type="button"
													className={button_class}
													onClick={open}>
													Change Background
												</button>
											} else {
												return <button type="button" className={button_class} onClick={open}>Add Background</button>;
											}
										}}
									/>
								</div>
								<div className="image">
									{item.mobile_url &&
										<img src={item.mobile_url} className="mobile" />
									}
									<MediaUpload
										onSelect={(media) => {
											items[i].mobile_url = media.url
											items[i].mobile_id = media.id
											setItems()
										}}
										allowedTypes={ ALLOWED_MEDIA_TYPES }
										value={ item.mobile_id }
										multiple={false}
										gallery={false}
										render={ ({open}) => {
											if (item.mobile_id){
												return <button
													type="button"
													className={button_class}
													onClick={open}>
													Change Mobile Image
												</button>
											} else {
												return <button type="button" className={button_class} onClick={open}>Add Mobile Image</button>;
											}
										}}
									/>
								</div>
							</div>



							<div className={contentClass}>



								<div className="text-wrap">
									<RichText
										tagName="p"
										placeholder={__( 'Title' )}
										value={ item.title }
										onChange={ value => {
											items[i].title = value
											setItems();
										}}
										className="title"
									/>
								</div>

								<div className="text-wrap textarea">
									<RichText
										tagName="p"
										placeholder={__( 'Description' )}
										value={ item.content }
										onChange={ value => {
											items[i].content = value
											setItems();
										}}
										className="text-content"
									/>
								</div>

								<div className="settings">
								<ToggleControl
									label={ __( 'Align Right' ) }
									checked={ item.right }
									onChange={ value => {
										items[i].right = value
										setItems();
									}}
								/>
								<ToggleControl
									label={ __( 'Modal' ) }
									checked={ item.modal }
									onChange={ value => {
										items[i].modal = value
										setItems();
									}}
								/>
								<ToggleControl
									label={ __( 'Read More' ) }
									checked={ item.more }
									onChange={ value => {
										items[i].more = value
										setItems();
									}}
								/>
								</div>
								<div className="link-wrapper">
									<button
									className="button add-link"
										onClick={ () => {
											items[i].show = ( items[i].show ) ? "" : " show";
											setItems()
										}}
										data-group={`.n4d-marquee-link-${instanceId}`}
									>{ (item.link_url) ? item.link_url : "Add Link" }</button>
									{item.link_url &&
									<a
										className="unlink"
										onClick={ () => {
											items[i].link_url = "";
											setItems()
										}}
									>
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path>
										</svg>
									</a>
									}
									<div className={"link-wrap "+item.show}>
										<LinkControl
											onChange={ ( next ) => {
												items[i].pid           = next.id
												items[i].link          = (items[i].title) ? items[i].title : next.title
												items[i].link_url      = next.url
												items[i].opensInNewTab = next.opensInNewTab
												items[i].show          = ""

												setItems()
											} }
											suggestionsQuery={ {
												type: 'post',
												subtype: 'post',
											} }
											value={{
												title: item.link,
												url: item.link_url,
												opensInNewTab: false
											}}
											data-target={`n4d-marquee-link-${instanceId}-${i}`}
										/>
									</div>
								</div>

							</div>
						</div>
					)
				})}
				</div>

			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				modal,
				overflow,
				items,
				scrollBottom
			}
		} = props;

		let section_id    = (anchor) ? anchor : "n4d-marquee-"+(id + 1);
		let section_class = "marquee";
		if (overflow) section_class += " overflow";

		let indicators = (items.length > 1) ? items.map( (value, index) => {
			let current = (index == 0) ? true : false;
			let active = (index == 0) ? "active" : "";
			let indicator = <button type="button" data-bs-target={`#${section_id}-carousel`} data-bs-slide-to={index} className={active} aria-current={current} aria-label={"Slide "+value.id}></button>;


			return indicator;
		} ) : "";

		let slides = (items.length > 0) ? items.map( (value, index) => {
//			const current  = (index == 0) ? true : false
			const active   = (index == 0) ? "active" : ""
			const right    = (value.right) ? " right" : ""
			const url      = (value.link_url) ? value.link_url : false
			const target   = (value.opensInNewTab) ? "_blank" : "_self"
			const toggle   = (value.modal) ? "modal" : false
			const isModal  = (value.modal) ? " preview-trigger" : ""
			let slides     = ""

			let content    = value.content.split('\n').join("<br />")

			if (value.more && url){
				content += '<br /><div class="btn btn-primary">อ่านต่อ</div>';
			}



			if (url){
				slides = (<a href={url} className={"carousel-item "+ active + isModal} data-bs-toggle={toggle} data-bs-target={modal} data-id={value.pid} data-title={value.title} data-mode="post" target={target} rel="noopener">
					<picture>
						{value.mobile_url &&
							<source media="(max-width:768px)" srcset={value.mobile_url} />
						}
						<img src={value.url} alt="..." />
					</picture>
					<div className={"carousel-caption"+right}>
						<h1 className="title" dangerouslySetInnerHTML={{__html:value.title}}></h1>
						<div className="entry" dangerouslySetInnerHTML={{__html:content}}></div>
					</div>
				</a>)
			} else {
				slides = (<div className={"carousel-item "+ active + isModal}>
					<picture>
						{value.mobile_url &&
							<source media="(max-width:768px)" srcset={value.mobile_url} />
						}
						<img src={value.url} alt="..." />
					</picture>
					<div className={"carousel-caption"+right}>
					<h1 className="title" dangerouslySetInnerHTML={{__html:value.title}}></h1>
						<div className="entry" dangerouslySetInnerHTML={{__html:content}}></div>
					</div>
				</div>)
			}


			return slides;
		}) : ""

		let scrollBottomBtn = (scrollBottom) ? <a class="scrollToBottom" data-target={"#"+section_id}><i class="fas fa-chevron-down"></i></a> : "";

		const prev = (items.length > 1) ? <button class="carousel-control-prev" type="button" data-bs-target={`#${section_id}-carousel`} data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button> : "";

		const next = (items.length > 1) ? <button class="carousel-control-next" type="button" data-bs-target={`#${section_id}-carousel`} data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button> : "";

		return (
			<div id={section_id} className={section_class}>
				<div id={section_id+"-carousel"} className="carousel slide" data-index={id} data-bs-ride="true">
					<div class="carousel-inner">{slides}</div>
					{prev}
					{next}
					<div class="container text">
						<div class="carousel-indicators">{indicators}</div>
					</div>
				</div>
				{scrollBottomBtn}
			</div>
		);
	}

});
registerBlockType( 'n4d/tabgallery', {
	title: 'N4D Tab Gallery',
	icon: 'excerpt-view',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		title: {
			type: 'string'
		},
		content: {
			type: 'string'
		},
		anchor: {
			type: 'string'
		},
		overflow: {
			type: 'Boolean',
		},
		half: {
			type: 'Boolean',
			default: false
		},
		inTab: {
			type: 'Boolean',
			default: false
		},
		ids: {
			type: 'array',
		},
		items: {
			type: 'array',
			default: []
		}

	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, overflow, items, half, inTab, title } = attributes;
		const ALLOWED_MEDIA_TYPES = [ 'image', 'video' ];
		const button_class = "components-button is-secondary changeBG";
		setAttributes( { id: instanceId } );

		const setItems = () => {
			const value = items.map((item, i) => {
				item.id    = i + 1;
				item.order = i;
				return item
			})
			setAttributes({ items: value})
		}
		const deleteItem = (n) => {
			let c = 0;
			const value = items.map((item, i) => {
				if (n !== i){
					c++
					item.id = c;
					return item
				}
			})
			setAttributes({ items: value.filter(function( el ) {
			   return el !== undefined;
			})})
		}

		function array_move(arr, old_index, new_index) {
			if (new_index >= arr.length) {
				var k = new_index - arr.length + 1;
				while (k--) {
					arr.push(undefined);
				}
			}
			arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);

			setAttributes({ items: arr})

			setItems()

			return arr;
		};


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ value => {
							setAttributes( { anchor: value } )
						}}
					/>
					<TextControl
						label={ __( 'Title' ) }
						value={ title }
						onChange={ value => {
							setAttributes( { title: value } )
						}}
					/>
					<ToggleControl
						label={ __( 'Overflow Container' ) }
						checked={ overflow }
						onChange={ value => {
							setAttributes( { overflow: value } );
						}}
					/>
					<ToggleControl
						label={ __( 'Half' ) }
						checked={ half }
						onChange={ value => {
							setAttributes( { half: value } );
						}}
					/>
					<ToggleControl
						label={ __( 'Content in Tab' ) }
						checked={ inTab }
						onChange={ value => {
							setAttributes( { inTab: value } );
						}}
					/>

				</PanelBody>
			</InspectorControls>,
			<div class="n4d-marquee">
				<div class="component-header">
					<span class="component-icon">
						N4D Tab Gallery
					</span>


					<button
						className="components-button add-nav"
						onClick={ () => {
							items.forEach((item, i) => {
								items[i].show = "";
							})

							items.push({
								id: items.length + 1,
								title: "",
								content: "",
								url: "",
								img_id: "",
								show: "",
								link: "",
								link_url: "",
								pid: false,
							});

							setItems()
						}}
					>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
							<path d="M18 11.2h-5.2V6h-1.6v5.2H6v1.6h5.2V18h1.6v-5.2H18z"></path>
						</svg>
					</button>
				</div>
				<div class="component-body">
				{items.map( (item, i) => {
					const divStyle = {
					//	backgroundImage: 'url('+item.url+')',
					}

					if (item.show == undefined) item.show = ''

					const itemClass = (item.right) ? "item right" : "item"
					const contentClass = (item.right) ? "content right" : "content"

					return (
						<div className={itemClass} style={divStyle}>
							<button
								type="button"
								className="components-button has-icon close-nav"
								aria-label="Remove Nav"
								onClick={()=>{
									deleteItem(i)
								}}
							>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path>
								</svg>
							</button>
							<div class="slide-control">
								<a
									class="btn"
									onClick={ () => {
										if (i > 0) array_move(items, i, i - 1)
									}}
								>
								<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"></path></svg>
								</a>
								<a
									class="btn"
									onClick={ () => {
										if (i < (items.length - 1)) array_move(items, i, i + 1)
									}}
								>
								<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>
								</a>
							</div>

							<div className="images">
								<div className="image">
									{item.url &&
										<img src={item.url} className="main" />
									}
									<MediaUpload
										onSelect={(media) => {
											items[i].url = media.url
											items[i].img_id = media.id
											setItems()
										}}
										allowedTypes={ ALLOWED_MEDIA_TYPES }
										value={ item.img_id }
										multiple={false}
										gallery={false}
										render={ ({open}) => {
											if (item.img_id){
												return <button
													type="button"
													className={button_class}
													onClick={open}>
													Change Background
												</button>
											} else {
												return <button type="button" className={button_class} onClick={open}>Add Background</button>;
											}
										}}
									/>
								</div>
							</div>



							<div className={contentClass}>



								<div className="text-wrap">
									<RichText
										tagName="p"
										placeholder={__( 'Title' )}
										value={ item.title }
										onChange={ value => {
											items[i].title = value
											setItems();
										}}
										className="title"
									/>
								</div>

								<div className="text-wrap textarea">
									<RichText
										tagName="p"
										placeholder={__( 'Description' )}
										value={ item.content }
										onChange={ value => {
											items[i].content = value
											setItems();
										}}
										className="text-content"
									/>
								</div>

								<div className="link-wrapper">
									<button
									className="button add-link"
										onClick={ () => {
											items[i].show = ( items[i].show ) ? "" : " show";
											setItems()
										}}
										data-group={`.n4d-marquee-link-${instanceId}`}
									>{ (item.link_url) ? item.link_url : "Add Link" }</button>
									{item.link_url &&
									<a
										className="unlink"
										onClick={ () => {
											items[i].link_url = "";
											setItems()
										}}
									>
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path>
										</svg>
									</a>
									}
									<div className={"link-wrap "+item.show}>
										<LinkControl
											onChange={ ( next ) => {
												items[i].pid           = next.id
												items[i].link          = (items[i].title) ? items[i].title : next.title
												items[i].link_url      = next.url
												items[i].opensInNewTab = next.opensInNewTab
												items[i].show          = ""

												setItems()
											} }
											suggestionsQuery={ {
												type: 'post',
												subtype: 'post',
											} }
											value={{
												title: item.link,
												url: item.link_url,
												opensInNewTab: false
											}}
											data-target={`n4d-marquee-link-${instanceId}-${i}`}
										/>
									</div>
								</div>

							</div>
						</div>
					)
				})}
				</div>

			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				overflow,
				items,
				half,
				inTab,
				title
			}
		} = props;

		let section_id    = (anchor) ? anchor : "n4d-tabGallery-"+(id + 1);
		let section_class = "tab-gallery row";
		section_class    += (inTab) ? " intab" : ""
		if (overflow) section_class += " overflow";
		const col1        = (half) ? 5 : 3
		const col2        = (half) ? 6 : 8
		const tabTitle    = (title !== "") ? (<h3 className="title">{title}</h3>) : ""

		let indicators = (items.length > 1) ? items.map( (value, index) => {
			let current  = (index == 0) ? true : false
			let btn  = "nav-link"
			btn  += (index == 0) ? " active" : "";
			let nav     = (<span className="nav-title">{value.title}</span>)
			let caption = (inTab) ? (<div className="nav-caption">{value.content}</div>) : ""

			let indicator = <button className={btn} id={section_id+"-"+index+"-tab"} data-bs-toggle="pill" data-bs-target={"#"+section_id+"-"+index} type="button" role="tab" aria-controls={section_id+"-"+index} aria-selected={current}>{nav}{caption}</button>


			if (value.title && value.url) return indicator;
		} ) : "";

		let slides = (items.length > 1) ? items.map( (value, index) => {
			const active   = (index == 0) ? " show active" : ""
			const url      = (value.link_url) ? value.link_url : false
			let content    = value.content.split('\n').join("<br />")
			let caption    = (!inTab) ? (<figcaption className="wp-element-caption" dangerouslySetInnerHTML={{__html:content}}></figcaption>) : ""
			let image      = (value.url) ? (<figure class="wp-block-image"><img src={value.url} alt={value.content} />{caption}</figure>) : ""
			let slides = (
				<div class={"tab-pane fade"+active} id={section_id+"-"+index} role="tabpanel" aria-labelledby={section_id+"-"+index+"-tab"} tabindex={index}>
				{image}
				</div>
			)

			return slides;
		}) : ""

		return (
			<div id={section_id} className={section_class}>
				<div className={"col-12 col-lg-"+col1}>
					<div class="nav flex-column" id={section_id+"-tab"} role="tablist" aria-orientation="vertical">{tabTitle}{indicators}</div>
				</div>
				<div className={"tab-content col-12 order-xs-n1 offset-lg-1 col-lg-"+col2} id={section_id+"-tabContent"}>{slides}</div>
			</div>
		);
	}

});
registerBlockType( 'n4d/box', {
	title: 'N4D Box',
	icon: <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24" role="img" aria-hidden="true" focusable="false"><path fill="#CCC" d="M512 512H0V0h512v512z"></path></svg>,
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		fullHeight: {
			type: 'boolean'
		},
		bgColor: {
			type: 'string'
		},
		textColor: {
			type: 'string'
		},
		noBorder: {
			type: 'boolean'
		},
		noPadding: {
			type: 'boolean'
		},
		noOverflow: {
			type: 'boolean'
		},
		bg: {
			type: 'string'
		},
		gradient: {
			type: 'boolean'
		},
		isLink: {
			type: 'boolean'
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, fullHeight, bgColor, textColor, noBorder, noPadding, noOverflow, bg, gradient, isLink } = attributes;
		let wrapClass = "n4d-box";
		const updateAnchor = value => {
			setAttributes( { anchor: value } );
		};
		const toggleFullHeight = value => {
			setAttributes( { fullHeight: value } );
		}

		setAttributes( { id: instanceId } );


		const divStyle = {
			backgroundImage: 'url('+bg+')',
			backgroundColor: bgColor,
			color: textColor
		}
		if (gradient){
			wrapClass += " gradient";
		}

		const ALLOWED_MEDIA_TYPES = [ 'image' ];
		const button_class = "components-button is-secondary";

		const removeMedia = () => {
			setAttributes({ bg: undefined });
		}


		return ([
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						icon={ 'edit' }
						label="Edit"
						onClick={ () => alert( 'Editing' ) }
					/>
					<ToolbarButton
						icon={ 'link' }
						label="Link"
					/>

				</ToolbarGroup>
			</BlockControls>,
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ updateAnchor }
					/>
					<ToggleControl
						label={ __( 'Single Link' ) }
						checked={ isLink }
						onChange={ value => {
							setAttributes({ isLink: value });
						} }
					/>

					<ToggleControl
						label={ __( 'Fill Height' ) }
						checked={ fullHeight }
						onChange={ toggleFullHeight }
					/>
					<ToggleControl
						label={ __( 'Hide Border' ) }
						checked={ noBorder }
						onChange={ value => {
							setAttributes({ noBorder: value });
						} }
					/>
					<ToggleControl
						label={ __( 'No Padding' ) }
						checked={ noPadding }
						onChange={ value => {
							setAttributes({ noPadding: value });
						} }
					/>
					<ToggleControl
						label={ __( 'No Overflow' ) }
						checked={ noOverflow }
						onChange={ value => {
							setAttributes({ noOverflow: value });
						} }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Background' ) }>
					<div className="mediaUpload-wrapper components-base-control">
						{ bg ? <img src={bg} /> : "" }
						<MediaUpload
							onSelect={(media) => {
								setAttributes( { bg: media.sizes.full.url } );
							}}
							allowedTypes={ ALLOWED_MEDIA_TYPES }
							value={ bg }
							render={ ({open}) => {
								if (bg){
									return <button
										type="button"
										className={button_class}
										onClick={removeMedia}>
										Change Background
									</button>
								} else {
									return <button type="button" className={button_class} onClick={open}>Select Background</button>;
								}

							}}
						/>
					</div>
					<ToggleControl
						label={ __( 'Gradient' ) }
						checked={ gradient }
						onChange={ value => {
							setAttributes({ gradient: value });
						} }
					/>

					<h4>TextColor</h4>
					<ColorPalette
						value={ textColor }
						onChange={value => {
							setAttributes( { textColor: value } );
						}}
					/>
					<h4>Background Color</h4>
					<ColorPalette
						value={ bgColor }
						onChange={value => {
							setAttributes( { bgColor: value } );
						}}
					/>
				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container" style={divStyle}>
				<InnerBlocks />
			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				fullHeight,
				bgColor,
				textColor,
				noBorder,
				noPadding,
				noOverflow,
				bg,
				gradient,
				isLink
			}
		} = props;

		let section_id = (anchor) ? anchor : "box-"+(id + 1);
		let section_class = "box";
		if (fullHeight){
			section_class += " h-100";
		}
		if (noBorder) {
			section_class += " no-border";
		}
		if (noPadding) {
			section_class += " p-0";
		}
		if (noOverflow) {
			section_class += " no-overflow";
		}
		const boxStyle = {
			backgroundColor: bgColor,
			color: textColor
		}

		if (bg){
			boxStyle.backgroundImage = 'url('+bg+')';
		}
		if (gradient){
			section_class += " gradient";
		}
		if (isLink){
			section_class += " isLink";
		}

		return (

			<div id={section_id} className={section_class} data-index={id} style={boxStyle}>
				<div class="box-content">
				<InnerBlocks.Content />
				</div>
			</div>

		);
	},

} );
registerBlockType( 'n4d/audio', {
	title: 'N4D Audio',
	icon: 'controls-volumeon',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		title: {
			type: 'string'
		},
		file_id: {
			type: 'string'
		},
		file: {
			type: 'string'
		},
		fileLength: {
			type: 'string'
		},
		fileFormat: {
			type: 'string'
		},
		download: {
			type: 'boolean'
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, title, file_id, file, download } = attributes
		let wrapClass = "n4d-audio"

		const ALLOWED_MEDIA_TYPES = [ 'audio' ]
		const button_class = "components-button is-secondary download"
		const divStyle = {}

		setAttributes( { id: instanceId } )

		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ value => setAttributes({anchor: value}) }
					/>
					<ToggleControl
						label={ __( 'Download' ) }
						checked={ download }
						onChange={ value => setAttributes({ download: value })}
					/>
				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container" style={divStyle}>
				<div class="n4d-gallery audio">
					<div class="n4d-header">
						<div class="components-placeholder__label">
							<span class="block-editor-block-icon">
								<span class="dashicons dashicons-controls-volumeon"></span>

							</span>


							<RichText
								tagName="h3"
								placeholder={__( 'Title' )}
								value={title}
								onChange={ value => setAttributes( {title: value} )}
								style={{margin:0}}
							/>

						</div>

						<div className="control">

						{download &&
							<span class="dashicons dashicons-download"></span>
						}
						<MediaUpload
							onSelect={(media) => {
								setAttributes( { file_id: media.id });
								setAttributes( { file: media.url });
								setAttributes( { fileLength: media.fileLength });
								setAttributes( { fileFormat: media.subtype});
							}}
							allowedTypes={ ALLOWED_MEDIA_TYPES }
							value={ file_id }
							render={ ({open}) => {
								if (file){
									return <button
										type="button"
										className={button_class}
										onClick={open}>
										Replace
									</button>
								} else {
									return <button type="button" className={button_class} onClick={open}>Select Audio</button>;
								}
							}}
						/>
						</div>

					</div>


					<audio controls src={file}></audio>

				</div>
			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				title,
				download,
				file,
				fileLength,
				fileFormat
			}
		} = props;

		let section_id    = (anchor) ? anchor : "box-audio"+(id + 1);
		let section_class = "box-media audio";
		let subtitle      = ( fileFormat == "mpeg"  ) ? " MP3" : "";

		return (

			<div id={section_id} className={section_class} data-index={id}>
				<div className="header">
					<div className="head">
						<a className="play icon-ic-media-play" data-target={"#"+section_id}></a>
						<div className="name">
							<small className="subtitle">ไฟล์เสียง{subtitle}</small>
							<h5 className="title">{title}</h5>
						</div>
					</div>
					{download &&
					<div className="download">
						<a href={file} className="btn btn-primary" target="_blank" rel="noopener">ดาวน์โหลด{subtitle}</a>
					</div>
					}
				</div>
				<audio src={file} data-target={"#"+section_id}></audio>

				<div class="progress" data-target={"#"+section_id}>
					<div class="progress-bar" role="progressbar" aria-label="play progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div className="playProgress">
					<div className="playTime">00.00</div>
					<div className="playLength">{fileLength}</div>
				</div>


			</div>

		);
	},

} );
registerBlockType( 'n4d/pagenav', {
	title: 'N4D Navigation',
	icon: 'menu',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		items: {
			type: 'array',
			default: []
		}
	},
	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, items } = attributes
		let wrapClass = "n4d-pagenav"

		const divStyle = {}

		setAttributes( { id: instanceId } )

		const setItems = () => {
			const value = items.map((item, i) => {
				item.id    = i + 1;
				item.order = i;
				return item
			})
			setAttributes({ items: value})
		}
		const deleteItem = (n) => {
			let c = 0;
			const value = items.map((item, i) => {
				if (n !== i){
					c++
					item.id = c;
					return item
				}
			})
			setAttributes({ items: value.filter(function( el ) {
			   return el !== undefined;
			})})
		}

		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ value => setAttributes({anchor: value}) }
					/>
				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container" style={divStyle}>
				<div class="n4d-wrap">

					{items.map( (nav, i) => {
						return (
							<div class="link">

								<button
									type="button"
									className="components-button has-icon close-nav"
									aria-label="Remove Nav"
									onClick={()=>{
										deleteItem(i)
									}}
								>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path>
									</svg>
								</button>



								<i className={nav.icon}></i>
								<TextControl
									placeholder={__( 'Title' )}
									value={items[i].title}
									onChange={ value => {
										items[i].title = value
										setItems();
									}}
									style={{margin:0}}
								/>
								<TextControl
									placeholder={ __( 'Icon' ) }
									value={ nav.icon }
									onChange={ value => {
										items[i].icon = value
										setItems();
									}}
								/>
								<button
								className="button add-link"
									onClick={(e)=>{
										const group  = e.target.dataset.group
										const dpdw   = document.querySelectorAll(group)
										dpdw.forEach((el, index) => {
											if (index !== i) {
												el.classList.remove("show")
											} else {
												if ( el.classList.contains("show") ){
													el.classList.remove("show")

												} else {
													el.classList.add("show")

												}
											}
										})

										items[i].show = " show"
										setItems()
									}}
									data-group={`.n4d-pagenav-${instanceId}`}
								>{ (nav.url) ? "Change Link" : "Add Link" }</button>
								<div id={`n4d-pagenav-${instanceId}-${i}`} className={"link-wrap n4d-pagenav-"+instanceId}>
								<LinkControl
									onChange={ ( next ) => {
										items[i].pid           = next.id
										items[i].title         = (items[i].title) ? items[i].title : next.title
										items[i].url           = next.url
										items[i].opensInNewTab = next.opensInNewTab
										items[i].show          = ""

										setItems()
									} }
									suggestionsQuery={ {
										type: 'post',
										subtype: 'page',
									} }
									value={{
										title: nav.title,
										url: nav.url,
										opensInNewTab: nav.opensInNewTab
									}}
									data-target={`n4d-pagenav-${instanceId}-${i}`}
								/>
								</div>
								<hr />
								<ToggleControl
									label={ __( 'Active' ) }
									checked={ items[i].active }
									onChange= {(value) => {
										items[i].active = value
										setItems()
									}}
								/>
							</div>
						)
					})}





					<button
						className="components-button add-nav"
						onClick={ () => {
							items.forEach((item, i) => {
								items[i].show = "";
							})


							items.push({
								id: items.length + 1
							});

							setItems()
						}}
					>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
							<path d="M18 11.2h-5.2V6h-1.6v5.2H6v1.6h5.2V18h1.6v-5.2H18z"></path>
						</svg>
					</button>


				</div>
			</div>
		]);

	}),
	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				items
			}
		} = props;

		let section_id    = (anchor) ? anchor : "nav-page-"+(id + 1);

		return (
			<nav id={section_id} className="subpages">
				<ul class="nav nav-justified">
					{items.map(nav => {
						return(
							<li className="nav-item">
								<a href={nav.url} className={(nav.active) ? "nav-link active" : "nav-link"} target={ (nav.opensInNewTab) ? "_blank" : "_self" } rel="noopener">
									<i className={nav.icon}></i>
									<span className="label" dangerouslySetInnerHTML={{__html:nav.title}}></span>
								</a>
							</li>
						)
					})}
				</ul>
			</nav>
		);
	},
} );
registerBlockType( 'n4d/timeline', {
	title: 'N4D Timeline',
	icon: 'post-status',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		link: {
			type: 'string'
		},
		time: {
			type: 'string'
		},
		description: {
			type: 'string'
		},
		steps: {
			type: 'boolean'
		},
		hideLine: {
			type: 'boolean'
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, time, description, steps, hideLine, link } = attributes
		const wrapClass = "n4d-timeline"
		const divStyle = {}

		setAttributes( { id: instanceId } )


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ value => setAttributes({anchor: value}) }
					/>
					<TextControl
						label={ __( 'Linked to Image' ) }
						value={ link }
						onChange={ value => setAttributes({link: value}) }
					/>
					<ToggleControl
						label={ __( 'Steps Mode' ) }
						checked={ steps }
						onChange={ value => setAttributes({steps: value}) }
					/>
					<ToggleControl
						label={ __( 'Hide Line' ) }
						checked={ hideLine }
						onChange={ value => setAttributes({hideLine: value}) }
					/>

				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container" style={divStyle}>
				<div class="n4d-wrap">


						{!steps &&
							<div className="time">
								<TextControl
									label={ __( 'Time' ) }
									value={ time }
									onChange={ value => setAttributes({time: value}) }
								/>
								<TextareaControl
									label={ __( 'Description' ) }
									value={ description }
									onChange={ value => setAttributes({description: value}) }
								/>
							</div>
						}
						{steps &&
							<div className="time">
								<TextControl
									label={ __( 'Step' ) }
									value={ time }
									onChange={ value => setAttributes({time: value}) }
								/>
							</div>
						}

					<div className="content">
					<InnerBlocks />
					</div>

				</div>
			</div>
		]);
	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				time,
				description,
				steps,
				hideLine,
				link
			}
		} = props;

		const section_id    = (anchor) ? anchor : "nav-page-"+(id + 1)
		let section_class = (steps) ? "timeline steps" :  "timeline"

		if (hideLine) section_class += " hideLine"

		return (
			<div id={section_id} className={section_class} data-target={link}>
			{!steps &&
				<div className="row">
					<div className="col-12 col-lg-3">
						<div className="time">
							<h5 className="title">{time}</h5>
							<div className="entry" dangerouslySetInnerHTML={{__html:description.split('\n').join("<br />")}}></div>
						</div>
					</div>
					<div className="col-12 col-lg-9">
						<div className="content">
							<InnerBlocks.Content />
						</div>
					</div>
				</div>
			}
			{steps &&
				<div className="step">
					<div className="title">{time}</div>
					<div className="content">
						<InnerBlocks.Content />
					</div>
				</div>
			}
			</div>
		);
	},

} );
registerBlockType( 'n4d/icon', {
	title: 'N4D Icon',
	icon: 'info',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		icon: {
			type: 'string'
		},
		color: {
			type: 'string',
			default: '#000'
		},
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, icon, color } = attributes
		const wrapClass = "n4d-icon"

		setAttributes( { id: instanceId } )

		const divStyle = {
			color: color
		}


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ value => setAttributes({anchor: value}) }
					/>

					<h4>Color</h4>
					<ColorPalette
						value={ color }
						onChange={value => setAttributes( { color: value } ) }
					/>

				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container" style={divStyle}>
				<div class="link">
					<i className={icon} style={divStyle}></i>
					<TextControl
						placeholder={ __( 'Icon' ) }
						value={ icon }
						onChange={ value => setAttributes({icon: value})}
					/>
				</div>
			</div>
		]);
	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				icon,
				color
			}
		} = props;

		let section_id    = (anchor) ? anchor : "n4d-icon-"+(id + 1);

		const divStyle = {
			color: color
		}

		return (
			<i id={section_id} className={icon} style={divStyle}></i>
		)
	},

} );
registerBlockType( 'n4d/anchors', {
	title: 'N4D Anchors',
	icon: <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M320 96a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zm21.1 80C367 158.8 384 129.4 384 96c0-53-43-96-96-96s-96 43-96 96c0 33.4 17 62.8 42.9 80H224c-17.7 0-32 14.3-32 32s14.3 32 32 32h32V448H208c-53 0-96-43-96-96v-6.1l7 7c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9L97 263c-9.4-9.4-24.6-9.4-33.9 0L7 319c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l7-7V352c0 88.4 71.6 160 160 160h80 80c88.4 0 160-71.6 160-160v-6.1l7 7c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-56-56c-9.4-9.4-24.6-9.4-33.9 0l-56 56c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l7-7V352c0 53-43 96-96 96H320V240h32c17.7 0 32-14.3 32-32s-14.3-32-32-32H341.1z"/></svg>,
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		columns: {
			type: 'number',
			default: 3
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, columns } = attributes;
		const wrapClass = "n4d-anchors";
		//const button_class = "components-button is-secondary";

		setAttributes( { id: instanceId } );


		function linkCols(){
			const html = []
			for (let i = 0; i < columns; i++){
				html.push(<div className="anchor-col">Links Column ({i})</div>)
			}
			return <div className="anchor-links">{html}</div>
		}


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ (value) => setAttributes( { anchor: value } ) }
					/>
					<TextControl
						label={ __( 'Columns' ) }
						type="number"
						max="6"
						min="0"
						value={ columns }
						onChange={(value) => setAttributes( { columns: Number(value) } )}
					/>
				</PanelBody>
			</InspectorControls>,
			<div className={wrapClass} key="container">
				{linkCols()}
			</div>
		]);
	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				columns
			}
		} = props;

		let section_id = (anchor) ? anchor : "anchors-"+(id + 1);
		let section_class = "anchors";


		return (
			<div id={section_id} className={section_class} data-index={id}>
				<div className="n4d-anchor-list" data-cols={columns}></div>
			</div>
		);
	},

} );
registerBlockType( 'n4d/modal', {
	title: 'N4D Modal',
	icon: 'editor-expand',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		modal: {
			type: 'string',
			default: '#preview-modal'
		},
		url: {
			type: 'string',
		}

	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { anchor, url, modal } = attributes;
		const ALLOWED_MEDIA_TYPES = [ 'image', 'video' ];
		const button_class = "components-button is-secondary";
		setAttributes( { id: instanceId } );

		const divStyle = {
			backgroundImage: 'url('+url+')',
		}


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ value => {
							setAttributes( { anchor: value } )
						}}
					/>
					<TextControl
						label={ __( 'Modal Target' ) }
						value={ modal }
						onChange={ value => {
							setAttributes( { modal: value } )
						}}
					/>

				</PanelBody>
			</InspectorControls>,
			<div class="n4d-modal" style={divStyle}>
				<MediaUpload
					onSelect={(media) => {
						setAttributes( { url: media.url } )
					}}
					allowedTypes={ ALLOWED_MEDIA_TYPES }
					value={ url }
					multiple={false}
					gallery={false}
					render={ ({open}) => {
						if (url){
							return <button
								type="button"
								className={button_class}
								onClick={open}>
								Change Image
							</button>
						} else {
							return <button type="button" className={button_class} onClick={open}>Add Image</button>;
						}
					}}
				/>
			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				modal,
				url,
			}
		} = props;

		let section_id    = (anchor) ? anchor : "n4d-modal-"+(id + 1);
		let section_class = "n4d-modal";
//$link  = "<a href=\"{$url}\" data-bs-toggle=\"modal\" data-bs-target=\"#preview-modal\" class=\"card-image preview-trigger\" data-title=\"{$title}\" data-src=\"{$src}\">".wp_get_attachment_image( $img_id, 'medium', false, array())."</a>";

		return (
			<div id={section_id} className={section_class}>
			{url &&
				<a href={url} className="preview-trigger" data-bs-toggle="modal" data-bs-target={modal} data-src={url}><img src={url} className="img-fluid" /></a>
			}
			</div>
		);
	}

});
registerBlockType( 'n4d/card', {
	title: 'N4D Card',
	icon: 'embed-generic',
	category: 'n4d-blocks',

	attributes: {
		id: {
			type: 'number'
		},
		anchor: {
			type: 'string'
		},
		title: {
			type: 'string'
		},
		body: {
			type: 'string'
		},
		url: {
			type: 'string',
		},
		img_url: {
			type: 'string',
		},
		img_width: {
			type: 'number',
		},
		img_height: {
			type: 'number',
		},
		img_size: {
			type: 'string',
			default: 'full'
		},
		items: {
			type: 'array',
			default: []
		},
		link_show: {
			type: 'boolean',
			default: false
		},
		items: {
			type: 'array',
			default: []
		},
		noBorder: {
			type: 'boolean',
			default: false
		},
		fillHeight: {
			type: 'boolean',
			default: false
		},
		noRadius: {
			type: 'boolean',
			default: false
		},
		padding: {
			type: 'number',
			default: 0
		},
		bgColor: {
			type: 'string',
			default: ''
		},
		readMore: {
			type: 'boolean',
			default: false
		},
		readMoreLabel: {
			type: 'string',
			default: 'Read More'
		},
		bodyLight: {
			type: 'boolean',
			default: false
		},
		placeholder: {
			type: 'boolean',
			default: false
		}
	},

	edit: withInstanceId( ( { attributes, setAttributes, instanceId } ) => {
		const { id, anchor, url, title, body, img_url, img_width, img_height, img_size, link_show, noBorder, fillHeight, noRadius, padding, bgColor, readMore, readMoreLabel, bodyLight, placeholder } = attributes;
		const ALLOWED_MEDIA_TYPES = [ 'image', 'video' ];
		const button_class = "components-button is-secondary";
		setAttributes( { id: instanceId } );

		const showLink = (force = false) => {
			const target = document.querySelector('#link-wrap-'+id)
			if (target.classList.contains("show") || force == true){
				target.classList.remove("show")
			}
			else {
				target.classList.add("show")
			}
		}

		let show = (link_show) ? " show" : ""

		const divStyle = {
			backgroundImage: 'url('+img_url+')',
		}
		const cardStyle = {}
		if (bgColor){
			cardStyle.backgroundColor = bgColor
		}
		if (noBorder){
			cardStyle.border = "none"
		}


		return ([
			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<TextControl
						label={ __( 'Anchor' ) }
						value={ anchor }
						onChange={ value => {
							setAttributes( { anchor: value } )
						}}
					/>
					<ToggleControl
						label={ __( 'Light Body Font Color' ) }
						checked={ bodyLight }
						onChange={ value => {
							setAttributes( { bodyLight: value } );
						}}
					/>
					<ToggleControl
						label={ __( 'No Border' ) }
						checked={ noBorder }
						onChange={ value => {
							setAttributes( { noBorder: value } );
						}}
					/>
					<ToggleControl
						label={ __( 'No Radius' ) }
						checked={ noRadius }
						onChange={ value => {
							setAttributes( { noRadius: value } );
						}}
					/>
					<ToggleControl
						label={ __( 'Fill Height' ) }
						checked={ fillHeight }
						onChange={ value => {
							setAttributes( { fillHeight: value } );
						}}
					/>
					<ToggleControl
						label={ __( 'Placeholder Image' ) }
						checked={ placeholder }
						onChange={ value => {
							setAttributes( { placeholder: value } );
						}}
					/>
					<TextControl
						label={ __( 'Padding' ) }
						type="number"
						max="5"
						min="0"
						value={ padding }
						onChange={(value) => setAttributes( { padding: Number(value) } )}
					/>
					<SelectControl
						label="Size"
						value={ img_size }
						options={ [
							{ label: 'Full', value: 'full' },
							{ label: 'Large', value: 'large' },
							{ label: 'Medium', value: 'medium' },
							{ label: 'Thumbnail', value: 'thumbnail' },
						] }
						onChange={ ( value ) => setAttributes( { img_size: value } ) }
						__nextHasNoMarginBottom
					/>

				</PanelBody>
				<PanelBody title={ __( 'Footer' ) }>
					<ToggleControl
						label={ __( 'Read More Button' ) }
						checked={ readMore }
						onChange={ value => {
							setAttributes( { readMore: value } );
						}}
					/>
					<TextControl
						label={ __( 'Read More Label' ) }
						value={ readMoreLabel }
						onChange={ value => {
							setAttributes( { readMoreLabel: value } )
						}}
					/>
				</PanelBody>
				<PanelBody title={ __( 'Background' ) }>
					<h4>Background Color</h4>
					<ColorPalette
						value={ bgColor }
						onChange={value => {
							setAttributes( { bgColor: value } );
						}}
					/>
				</PanelBody>
			</InspectorControls>,
			<div class="n4d-card" style={cardStyle}>


				<div class="n4d-card-image" style={divStyle}>
					<MediaUpload
						onSelect={(media) => {
							if (media.sizes[img_size]){
								setAttributes( { img_url: media.sizes[img_size].url } )
								setAttributes( { img_width: media.sizes[img_size].width } )
								setAttributes( { img_height: media.sizes[img_size].height } )
							} else {
								setAttributes( { img_url: media.url } )
								setAttributes( { img_width: media.width } )
								setAttributes( { img_height: media.height } )
							}
						}}
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						value={ url }
						multiple={false}
						gallery={false}
						render={ ({open}) => {
							if (url){
								return <button
									type="button"
									className={button_class}
									onClick={open}>
									Change Image
								</button>
							} else {
								return <button type="button" className={button_class} onClick={open}>Add Image</button>;
							}
						}}
					/>
				</div>
				<RichText
					tagName="h3"
					placeholder={__( 'Title' )}
					value={title}
					onChange={ value => {
						setAttributes( { title: value } )
					}}
				/>
				<RichText
					tagName="p"
					placeholder={__( 'Body' )}
					value={body}
					onChange={ value => {
						setAttributes( { body: value } )
					}}
				/>

				<div className="link-wrapper">
					<button
					className="button add-link"
						onClick={ e => {
							showLink()

						}}
					>{ (url) ? url : "Add Link" }</button>
					{url &&
					<a
						className="unlink"
						onClick={ e => {
							showLink(true)
							setAttributes( { url: "" } )
						}}
					>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path>
						</svg>
					</a>
					}
					<div id={"link-wrap-"+id} className={"link-wrap"}>
						<LinkControl
							onChange={ ( next ) => {
								setAttributes( { url: next.url } )
								showLink(true)
							} }
							suggestionsQuery={ {
								type: 'post',
								subtype: 'page',
							} }
							value={{
								url: url,
								opensInNewTab: false
							}}
						/>
					</div>
				</div>
			</div>
		]);

	}),

	save: (props) => {
		const {
			attributes: {
				id,
				anchor,
				url,
				title,
				body,
				img_url,
				img_width,
				img_height,
				noBorder,
				noRadius,
				fillHeight,
				padding,
				bgColor,
				bodyLight,
				readMore,
				readMoreLabel,
				placeholder
			}
		} = props;

		let section_id     = (anchor) ? anchor : "n4d-card-"+(id + 1);
		let section_class  = "card";
		section_class     += (noBorder) ? " no-border" : ""
		section_class     += (fillHeight) ? " h-100" : ""
		section_class     += (noRadius) ? " no-radius" : ""
		section_class     += (padding && padding !== 0) ? " p-"+padding : ""
		section_class     += (bodyLight) ? " color-light" : ""

		const image        = (img_url || placeholder) ? (<img src={img_url} className="card-img-top" width={img_width} height={img_height} />) : ""
		const image_link   = (url) ? (<a href={url}>{image}</a>) : image

		const title_link   = (url) ? (<a href={url}>{title}</a>) : title

		const cardStyle      = {}

		if (bgColor){
			cardStyle.backgroundColor = bgColor;
		}

		return (
			<div id={section_id} className={section_class} style={cardStyle}>
				{image &&
					<div className="card-image">{image_link}</div>
				}
				<div className="card-body">
					{title &&
						<h5 className="card-title">{title_link}</h5>
					}
					{body &&
						<div className="card-text" dangerouslySetInnerHTML={{__html:body}}></div>
					}
				</div>
				{
					readMore &&
						<div className="card-footer">
							<a href={url} className="btn btn-secondary">{readMoreLabel}</a>
						</div>
				}
			</div>
		);
	}

});