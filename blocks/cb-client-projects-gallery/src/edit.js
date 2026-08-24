import { __ } from '@wordpress/i18n';
import { useBlockProps, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, TextareaControl, SelectControl, Button } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

export default function Edit( { attributes, setAttributes } ) {
	const { layout, eyebrow, heading, intro, images } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	const media = useSelect(
		( select ) =>
			images.length
				? images.map( ( id ) => select( coreStore ).getMedia( id ) ).filter( Boolean )
				: [],
		[ images ]
	);

	function removeImage( id ) {
		setAttributes( { images: images.filter( ( imageId ) => imageId !== id ) } );
	}

	return (
		<div { ...blockProps }>
			<p className="cb-hts-js-2026-editor-block__title">{ __( 'CB Client Projects Gallery', 'cb-hts-js-2026' ) }</p>
			<SelectControl
				label={ __( 'Layout', 'cb-hts-js-2026' ) }
				value={ layout }
				options={ [
					{ label: __( 'Mosaic', 'cb-hts-js-2026' ), value: 'mosaic' },
					{ label: __( 'Feature tile', 'cb-hts-js-2026' ), value: 'feature' },
				] }
				onChange={ ( value ) => setAttributes( { layout: value } ) }
				help={ __(
					'"Mosaic" repeats a five-tile rhythm. "Feature" leads with one large tile followed by half-width tiles, and shows media-library captions on hover.',
					'cb-hts-js-2026'
				) }
			/>
			<TextControl
				label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
				value={ eyebrow }
				onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
			/>
			<TextControl
				label={ __( 'Heading', 'cb-hts-js-2026' ) }
				value={ heading }
				onChange={ ( value ) => setAttributes( { heading: value } ) }
				help={ __( 'Wrap emphasised text in a <span> to render it italic + orange.', 'cb-hts-js-2026' ) }
			/>
			<TextareaControl
				label={ __( 'Intro', 'cb-hts-js-2026' ) }
				value={ intro }
				onChange={ ( value ) => setAttributes( { intro: value } ) }
				help={ __( 'Blank lines start a new paragraph.', 'cb-hts-js-2026' ) }
			/>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Images', 'cb-hts-js-2026' ) }</label>
				{ media.length > 0 && (
					<ul className="cb-hts-js-2026-gallery-field__preview" style={ { display: 'flex', flexWrap: 'wrap', gap: '8px', padding: 0, margin: '0 0 8px', listStyle: 'none' } }>
						{ media.map( ( item ) => (
							<li key={ item.id } style={ { position: 'relative' } }>
								<img
									src={ item.media_details?.sizes?.thumbnail?.source_url || item.source_url }
									alt=""
									style={ { width: '80px', height: '80px', objectFit: 'cover', display: 'block', background: '#fff', border: '1px solid #ccc' } }
								/>
								<Button
									size="small"
									isDestructive
									label={ __( 'Remove', 'cb-hts-js-2026' ) }
									onClick={ () => removeImage( item.id ) }
									style={ { position: 'absolute', top: 0, right: 0, minWidth: '20px', height: '20px', padding: 0, background: 'rgba(0,0,0,.6)', color: '#fff' } }
								>
									&times;
								</Button>
							</li>
						) ) }
					</ul>
				) }
				<MediaUploadCheck>
					<MediaUpload
						onSelect={ ( selected ) => setAttributes( { images: selected.map( ( item ) => item.id ) } ) }
						allowedTypes={ [ 'image' ] }
						multiple
						gallery
						value={ images }
						render={ ( { open } ) => (
							<Button variant="secondary" onClick={ open }>
								{ images.length ? __( 'Edit Gallery', 'cb-hts-js-2026' ) : __( 'Select Images', 'cb-hts-js-2026' ) }
							</Button>
						) }
					/>
				</MediaUploadCheck>
			</div>
		</div>
	);
}
