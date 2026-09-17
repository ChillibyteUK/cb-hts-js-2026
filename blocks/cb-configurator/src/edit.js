import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl, Button } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { tag, headline, intro, features, ctaText, ctaUrl, ctaTarget, imageId, imageUrl, imageAlt } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Configurator', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<TextControl
				label={ __( 'Tag', 'cb-hts-js-2026' ) }
				value={ tag }
				onChange={ ( value ) => setAttributes( { tag: value } ) }
				help={ __( 'Small pill above the headline, e.g. "Online 3D Configurator".', 'cb-hts-js-2026' ) }
			/>
			<TextareaControl
				label={ __( 'Headline', 'cb-hts-js-2026' ) }
				value={ headline }
				onChange={ ( value ) => setAttributes( { headline: value } ) }
				help={ __( 'Wrap emphasised text in a <span> to render it italic + orange.', 'cb-hts-js-2026' ) }
			/>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Intro', 'cb-hts-js-2026' ) }</label>
				<RichText
					tagName="div"
					multiline="p"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Intro', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Intro', 'cb-hts-js-2026' ) }
					value={ intro }
					onChange={ ( value ) => setAttributes( { intro: value } ) }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
				/>
			</div>
			<TextareaControl
				label={ __( 'Features', 'cb-hts-js-2026' ) }
				value={ features }
				onChange={ ( value ) => setAttributes( { features: value } ) }
				help={ __( 'One per line. Inline <strong>/<em> allowed.', 'cb-hts-js-2026' ) }
			/>
			<TextControl
				label={ __( 'CTA Text', 'cb-hts-js-2026' ) }
				value={ ctaText }
				onChange={ ( value ) => setAttributes( { ctaText: value } ) }
				help={ __( 'Defaults to "Launch the configurator" if left blank.', 'cb-hts-js-2026' ) }
			/>
			<TextControl
				type="url"
				label={ __( 'CTA URL', 'cb-hts-js-2026' ) }
				value={ ctaUrl }
				onChange={ ( value ) => setAttributes( { ctaUrl: value } ) }
			/>
			<ToggleControl
				label={ __( 'Open CTA in a new tab', 'cb-hts-js-2026' ) }
				checked={ ctaTarget }
				onChange={ ( value ) => setAttributes( { ctaTarget: value } ) }
			/>
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
		</EditorBlockShell>
	);
}
