import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl, Button } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const {
		tag,
		h1,
		lede,
		bullets,
		ctaPrimaryText,
		ctaPrimaryUrl,
		ctaPrimaryTarget,
		ctaSecondaryText,
		ctaSecondaryUrl,
		ctaSecondaryTarget,
		imageId,
		imageUrl,
		imageAlt,
		badgeNumber,
		badgeSuffix,
		badgeLabel,
	} = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<div { ...blockProps }>
			<p className="cb-hts-js-2026-editor-block__title">{ __( 'CB Product / Project Hero', 'cb-hts-js-2026' ) }</p>
			<TextControl
				label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
				value={ tag }
				onChange={ ( value ) => setAttributes( { tag: value } ) }
			/>
			<TextControl
				label={ __( 'H1', 'cb-hts-js-2026' ) }
				value={ h1 }
				onChange={ ( value ) => setAttributes( { h1: value } ) }
				help={ __( 'Wrap emphasised text in a <span> to render it italic + orange.', 'cb-hts-js-2026' ) }
			/>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Lede', 'cb-hts-js-2026' ) }</label>
				<RichText
					tagName="div"
					multiline="p"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Lede', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Lede', 'cb-hts-js-2026' ) }
					value={ lede }
					onChange={ ( value ) => setAttributes( { lede: value } ) }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
				/>
				<p className="cb-hts-js-2026-editor-field__help">{ __( 'First paragraph renders larger as a subtitle.', 'cb-hts-js-2026' ) }</p>
			</div>
			<TextareaControl
				label={ __( 'Bullets', 'cb-hts-js-2026' ) }
				value={ bullets }
				onChange={ ( value ) => setAttributes( { bullets: value } ) }
				help={ __( 'One bullet per line. <strong>/<em> allowed for inline emphasis.', 'cb-hts-js-2026' ) }
			/>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Cta primary Text', 'cb-hts-js-2026' ) }
						value={ ctaPrimaryText }
						onChange={ ( value ) => setAttributes( { ctaPrimaryText: value } ) }
					/>
					<TextControl
						type="url"
						label={ __( 'Cta primary URL', 'cb-hts-js-2026' ) }
						value={ ctaPrimaryUrl }
						onChange={ ( value ) => setAttributes( { ctaPrimaryUrl: value } ) }
					/>
					<ToggleControl
						label={ __( 'Open Cta primary in a new tab', 'cb-hts-js-2026' ) }
						checked={ ctaPrimaryTarget }
						onChange={ ( value ) => setAttributes( { ctaPrimaryTarget: value } ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Cta secondary Text', 'cb-hts-js-2026' ) }
						value={ ctaSecondaryText }
						onChange={ ( value ) => setAttributes( { ctaSecondaryText: value } ) }
					/>
					<TextControl
						type="url"
						label={ __( 'Cta secondary URL', 'cb-hts-js-2026' ) }
						value={ ctaSecondaryUrl }
						onChange={ ( value ) => setAttributes( { ctaSecondaryUrl: value } ) }
					/>
					<ToggleControl
						label={ __( 'Open Cta secondary in a new tab', 'cb-hts-js-2026' ) }
						checked={ ctaSecondaryTarget }
						onChange={ ( value ) => setAttributes( { ctaSecondaryTarget: value } ) }
					/>
				</div>
			</div>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Image', 'cb-hts-js-2026' ) }</label>
				<MediaUploadCheck>
					<MediaUpload
						onSelect={ ( media ) =>
							setAttributes( {
								imageId: media.id,
								imageUrl: media.url,
								imageAlt: media.alt || '',
							} )
						}
						allowedTypes={ [ 'image' ] }
						value={ imageId }
						render={ ( { open } ) => (
							<div className="cb-hts-js-2026-editor-field__control">
								{ imageUrl && (
									<img
										src={ imageUrl }
										alt={ imageAlt }
										style={ { maxWidth: '200px', display: 'block', marginBottom: '8px' } }
									/>
								) }
								<Button variant="secondary" onClick={ open }>
									{ imageUrl ? __( 'Replace Image', 'cb-hts-js-2026' ) : __( 'Select Image', 'cb-hts-js-2026' ) }
								</Button>
							</div>
						) }
					/>
				</MediaUploadCheck>
			</div>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '33 1 0%' } }>
					<TextControl
						label={ __( 'Badge number', 'cb-hts-js-2026' ) }
						value={ badgeNumber }
						onChange={ ( value ) => setAttributes( { badgeNumber: value } ) }
						help={ __( 'e.g. "500"', 'cb-hts-js-2026' ) }
					/>
				</div>
				<div style={ { flex: '33 1 0%' } }>
					<TextControl
						label={ __( 'Badge suffix', 'cb-hts-js-2026' ) }
						value={ badgeSuffix }
						onChange={ ( value ) => setAttributes( { badgeSuffix: value } ) }
						help={ __( 'Rendered in superscript. e.g. "+"', 'cb-hts-js-2026' ) }
					/>
				</div>
				<div style={ { flex: '33 1 0%' } }>
					<TextareaControl
						label={ __( 'Badge label', 'cb-hts-js-2026' ) }
						value={ badgeLabel }
						onChange={ ( value ) => setAttributes( { badgeLabel: value } ) }
						help={ __( 'Newlines become line breaks.', 'cb-hts-js-2026' ) }
					/>
				</div>
			</div>
		</div>
	);
}
