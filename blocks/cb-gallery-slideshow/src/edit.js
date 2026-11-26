import { __ } from '@wordpress/i18n';
import { useBlockProps, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, TextareaControl, Button, Notice } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import SideloadImage from '../../_shared/SideloadImage';

const MIN_IMAGES = 3;

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, heading, lede, images } = attributes;
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
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Gallery Slideshow', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
						value={ eyebrow }
						onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Title', 'cb-hts-js-2026' ) }
						value={ heading }
						onChange={ ( value ) => setAttributes( { heading: value } ) }
						help={ __( 'Wrap emphasised text in a <span> to render it italic + orange.', 'cb-hts-js-2026' ) }
					/>
				</div>
			</div>
			<TextareaControl
				label={ __( 'Lede', 'cb-hts-js-2026' ) }
				value={ lede }
				onChange={ ( value ) => setAttributes( { lede: value } ) }
				help={ __( 'Blank lines start a new paragraph.', 'cb-hts-js-2026' ) }
			/>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Images', 'cb-hts-js-2026' ) }</label>
				{ images.length < MIN_IMAGES && (
					<Notice status="warning" isDismissible={ false }>
						{ __( 'Add at least three images — the slideshow will not display until there are three.', 'cb-hts-js-2026' ) }
					</Notice>
				) }
				{ media.length > 0 && (
					<ul style={ { display: 'flex', flexWrap: 'wrap', gap: '8px', padding: 0, margin: '8px 0', listStyle: 'none' } }>
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
				<SideloadImage
					onSelect={ ( item ) => setAttributes( { images: [ ...images, item.id ] } ) }
				/>
			</div>
		</EditorBlockShell>
	);
}
