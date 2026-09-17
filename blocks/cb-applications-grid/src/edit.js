import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, lede } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Applications Grid', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
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
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Text content', 'cb-hts-js-2026' ) }</label>
				<RichText
					tagName="div"
					multiline="p"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Text content', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Text content', 'cb-hts-js-2026' ) }
					value={ lede }
					onChange={ ( value ) => setAttributes( { lede: value } ) }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
				/>
			</div>
			<p>
				{ __(
					'Cards are pulled live from the Application post type (menu order, then date) — not editable here.',
					'cb-hts-js-2026'
				) }
			</p>
		</EditorBlockShell>
	);
}
