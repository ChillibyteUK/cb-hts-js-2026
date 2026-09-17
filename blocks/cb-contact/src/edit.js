import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, body, coverage, noteTitle, noteBody, formTitle, formSubtitle, formShortcode } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Contact', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<TextControl
				label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
				value={ eyebrow }
				onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
			/>
			<TextareaControl
				label={ __( 'Headline', 'cb-hts-js-2026' ) }
				value={ headline }
				onChange={ ( value ) => setAttributes( { headline: value } ) }
				help={ __( 'Wrap highlighted text in <span>.', 'cb-hts-js-2026' ) }
			/>
			<TextareaControl
				label={ __( 'Body', 'cb-hts-js-2026' ) }
				value={ body }
				onChange={ ( value ) => setAttributes( { body: value } ) }
			/>
			<TextareaControl
				label={ __( 'Coverage', 'cb-hts-js-2026' ) }
				value={ coverage }
				onChange={ ( value ) => setAttributes( { coverage: value } ) }
			/>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Note Title', 'cb-hts-js-2026' ) }
						value={ noteTitle }
						onChange={ ( value ) => setAttributes( { noteTitle: value } ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextareaControl
						label={ __( 'Note Body', 'cb-hts-js-2026' ) }
						value={ noteBody }
						onChange={ ( value ) => setAttributes( { noteBody: value } ) }
					/>
				</div>
			</div>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Form Title', 'cb-hts-js-2026' ) }
						value={ formTitle }
						onChange={ ( value ) => setAttributes( { formTitle: value } ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextareaControl
						label={ __( 'Form Subtitle', 'cb-hts-js-2026' ) }
						value={ formSubtitle }
						onChange={ ( value ) => setAttributes( { formSubtitle: value } ) }
					/>
				</div>
			</div>
			<TextControl
				label={ __( 'Form Shortcode', 'cb-hts-js-2026' ) }
				value={ formShortcode }
				onChange={ ( value ) => setAttributes( { formShortcode: value } ) }
				help={ __( 'Paste the full Gravity Forms shortcode, e.g. [gravityform id="1" title="false" description="false" ajax="true"].', 'cb-hts-js-2026' ) }
			/>
		</EditorBlockShell>
	);
}
