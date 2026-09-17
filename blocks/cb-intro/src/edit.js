import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, body, signature, highlights } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Intro', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
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
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Body', 'cb-hts-js-2026' ) }</label>
				<RichText
					tagName="div"
					multiline="p"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Body', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Body', 'cb-hts-js-2026' ) }
					value={ body }
					onChange={ ( value ) => setAttributes( { body: value } ) }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
				/>
				<p className="cb-hts-js-2026-editor-field__help">{ __( 'First paragraph renders larger as a lede.', 'cb-hts-js-2026' ) }</p>
			</div>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextareaControl
						label={ __( 'Signature', 'cb-hts-js-2026' ) }
						value={ signature }
						onChange={ ( value ) => setAttributes( { signature: value } ) }
						help={ __( 'Trust line shown below the body. Inline <strong> allowed.', 'cb-hts-js-2026' ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextareaControl
						label={ __( 'Highlights', 'cb-hts-js-2026' ) }
						value={ highlights }
						onChange={ ( value ) => setAttributes( { highlights: value } ) }
						help={ __( 'One per line.', 'cb-hts-js-2026' ) }
					/>
				</div>
			</div>
		</EditorBlockShell>
	);
}
