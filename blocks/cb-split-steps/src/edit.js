import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const STEP_FIELDS = [
	{ name: 'title', label: __( 'Title', 'cb-hts-js-2026' ), type: 'text' },
	{ name: 'body', label: __( 'Body', 'cb-hts-js-2026' ), type: 'textarea' },
	{ name: 'link', label: __( 'Link', 'cb-hts-js-2026' ), type: 'link', linkTarget: true },
];
const EMPTY_STEP = { title: '', body: '', link: '', linkText: '', linkTarget: false };

/**
 * Makes a legacy plain-text value safe to hand to RichText. RichText's
 * editable content silently fails to render at all when given a value with
 * no wrapping HTML tag whatsoever — and this block's body was a plain
 * textarea before, so existing rows are markup-free text with literal
 * newlines. Blank lines become separate paragraphs (matching the
 * multiline="p" mode below), single newlines become <br>. Values that
 * already contain markup (including everything RichText itself has saved)
 * pass through untouched; on the first edit RichText rewrites proper
 * markup back into the attribute, so this only ever bridges that one
 * legacy moment.
 */
function toSafeRichTextHtml( value ) {
	const text = value || '';
	if ( '' === text.trim() || text.includes( '<' ) ) {
		return text;
	}
	return text
		.split( /\n\s*\n/ )
		.map( ( para ) => `<p>${ para.replace( /\r\n|\r|\n/g, '<br>' ).trim() }</p>` )
		.join( '' );
}

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, body, steps, watermark } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Split Steps', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
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
					value={ toSafeRichTextHtml( body ) }
					onChange={ ( value ) => setAttributes( { body: value } ) }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link', 'cb-hts-js-2026/small-text' ] }
				/>
			</div>
			<RepeaterField
				label={ __( 'Steps', 'cb-hts-js-2026' ) }
				value={ steps }
				onChange={ ( value ) => setAttributes( { steps: value } ) }
				fields={ STEP_FIELDS }
				emptyRow={ EMPTY_STEP }
			/>
			<TextControl
				label={ __( 'Watermark', 'cb-hts-js-2026' ) }
				value={ watermark }
				onChange={ ( value ) => setAttributes( { watermark: value } ) }
				help={ __( 'Optional giant background word. Leave empty for none.', 'cb-hts-js-2026' ) }
			/>
		</EditorBlockShell>
	);
}
