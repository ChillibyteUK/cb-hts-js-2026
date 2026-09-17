import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const FIELDS = [
	{ name: 'title', label: __( 'Title', 'cb-hts-js-2026' ), type: 'text', help: __( 'Leave blank to use the file’s own title.', 'cb-hts-js-2026' ) },
	{ name: 'meta', label: __( 'Sub-line', 'cb-hts-js-2026' ), type: 'text', help: __( 'Optional, e.g. "Width 15m · eave height 4.00m–7.00m".', 'cb-hts-js-2026' ) },
	{ name: 'file', label: __( 'File', 'cb-hts-js-2026' ), type: 'file', mimeTypes: [ 'application/pdf' ] },
];

const EMPTY_ROW = { title: '', meta: '', file: 0 };

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, intro, items } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Downloads', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<TextControl
				label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
				value={ eyebrow }
				onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
			/>
			<TextareaControl
				label={ __( 'Headline', 'cb-hts-js-2026' ) }
				value={ headline }
				onChange={ ( value ) => setAttributes( { headline: value } ) }
				help={ __( '<span> italicises and colours a phrase. <br> forces a line break.', 'cb-hts-js-2026' ) }
			/>
			<TextareaControl
				label={ __( 'Intro', 'cb-hts-js-2026' ) }
				value={ intro }
				onChange={ ( value ) => setAttributes( { intro: value } ) }
				help={ __( 'Optional. Sits to the right of the headline. Newlines become line breaks.', 'cb-hts-js-2026' ) }
			/>
			<RepeaterField
				label={ __( 'Downloads', 'cb-hts-js-2026' ) }
				value={ items }
				onChange={ ( value ) => setAttributes( { items: value } ) }
				fields={ FIELDS }
				emptyRow={ EMPTY_ROW }
			/>
		</EditorBlockShell>
	);
}
