import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl, Button } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const {
		imageId,
		imageUrl,
		imageAlt,
		eyebrow,
		headline,
		content,
		buttonText,
		buttonUrl,
		buttonTarget,
		buttonSecondaryText,
		buttonSecondaryUrl,
		buttonSecondaryTarget,
	} = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Image CTA', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Background Image', 'cb-hts-js-2026' ) }</label>
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
			<TextControl
				label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
				value={ eyebrow }
				onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
			/>
			<TextareaControl
				label={ __( 'Headline', 'cb-hts-js-2026' ) }
				value={ headline }
				onChange={ ( value ) => setAttributes( { headline: value } ) }
				help={ __( 'Wrap emphasised text in a <span> to render it italic + orange.', 'cb-hts-js-2026' ) }
			/>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Content', 'cb-hts-js-2026' ) }</label>
				<RichText
					tagName="div"
					multiline="p"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Content', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Content', 'cb-hts-js-2026' ) }
					value={ content }
					onChange={ ( value ) => setAttributes( { content: value } ) }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
				/>
			</div>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Button Text', 'cb-hts-js-2026' ) }
						value={ buttonText }
						onChange={ ( value ) => setAttributes( { buttonText: value } ) }
					/>
					<TextControl
						type="url"
						label={ __( 'Button URL', 'cb-hts-js-2026' ) }
						value={ buttonUrl }
						onChange={ ( value ) => setAttributes( { buttonUrl: value } ) }
					/>
					<ToggleControl
						label={ __( 'Open button in a new tab', 'cb-hts-js-2026' ) }
						checked={ buttonTarget }
						onChange={ ( value ) => setAttributes( { buttonTarget: value } ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Secondary Button Text', 'cb-hts-js-2026' ) }
						value={ buttonSecondaryText }
						onChange={ ( value ) => setAttributes( { buttonSecondaryText: value } ) }
					/>
					<TextControl
						type="url"
						label={ __( 'Secondary Button URL', 'cb-hts-js-2026' ) }
						value={ buttonSecondaryUrl }
						onChange={ ( value ) => setAttributes( { buttonSecondaryUrl: value } ) }
					/>
					<ToggleControl
						label={ __( 'Open secondary button in a new tab', 'cb-hts-js-2026' ) }
						checked={ buttonSecondaryTarget }
						onChange={ ( value ) => setAttributes( { buttonSecondaryTarget: value } ) }
					/>
				</div>
			</div>
		</EditorBlockShell>
	);
}
