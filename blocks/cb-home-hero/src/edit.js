import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl, Button } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { metaItems, h1Line1Text, h1Line1Highlight, h1Line2Text, h1Line2Highlight, lede, bullets, ctaPrimaryText, ctaPrimaryUrl, ctaPrimaryTarget, ctaSecondaryText, ctaSecondaryUrl, ctaSecondaryTarget, imageId, imageUrl, imageAlt, badgeNumber, badgeSuffix, badgeLabel } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<div { ...blockProps }>
			<p className="cb-hts-js-2026-editor-block__title">CB Home Hero</p>
			<TextareaControl
				label={ __( 'Meta items', 'cb-hts-js-2026' ) }
				value={ metaItems }
				onChange={ ( value ) => setAttributes( { metaItems: value } ) }
				help={ __( 'One item per line. Rendered as a meta strip with dividers between items.', 'cb-hts-js-2026' ) }
			/>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'H1 line1 text', 'cb-hts-js-2026' ) }
						value={ h1Line1Text }
						onChange={ ( value ) => setAttributes( { h1Line1Text: value } ) }
						help={ __( 'e.g. "Built in " (include trailing space if needed).', 'cb-hts-js-2026' ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'H1 line1 highlight', 'cb-hts-js-2026' ) }
						value={ h1Line1Highlight }
						onChange={ ( value ) => setAttributes( { h1Line1Highlight: value } ) }
						help={ __( 'Wrapped automatically in the underline style. e.g. "14 days."', 'cb-hts-js-2026' ) }
					/>
				</div>
			</div>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'H1 line2 text', 'cb-hts-js-2026' ) }
						value={ h1Line2Text }
						onChange={ ( value ) => setAttributes( { h1Line2Text: value } ) }
						help={ __( 'e.g. "Engineered for " (include trailing space if needed).', 'cb-hts-js-2026' ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'H1 line2 highlight', 'cb-hts-js-2026' ) }
						value={ h1Line2Highlight }
						onChange={ ( value ) => setAttributes( { h1Line2Highlight: value } ) }
						help={ __( 'Wrapped automatically in the orange italic accent style. e.g. "decades."', 'cb-hts-js-2026' ) }
					/>
				</div>
			</div>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Lede', 'cb-hts-js-2026' ) }</label>
				<RichText
					tagName="div"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Lede', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Lede', 'cb-hts-js-2026' ) }
					value={ lede }
					onChange={ ( value ) => setAttributes( { lede: value } ) }
				/>
			</div>
			<TextareaControl
				label={ __( 'Bullets', 'cb-hts-js-2026' ) }
				value={ bullets }
				onChange={ ( value ) => setAttributes( { bullets: value } ) }
				help={ __( 'One bullet per line. Bold and italic are allowed for inline emphasis.', 'cb-hts-js-2026' ) }
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
