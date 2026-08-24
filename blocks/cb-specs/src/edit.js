import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';

const FIELDS = [
	{ name: 'label', label: __( 'Label', 'cb-hts-js-2026' ), type: 'text' },
	{ name: 'value', label: __( 'Value', 'cb-hts-js-2026' ), type: 'textarea', help: __( 'Newlines become line breaks.', 'cb-hts-js-2026' ) },
];

const EMPTY_ROW = { label: '', value: '' };

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, headline, intro, rows } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<div { ...blockProps }>
			<p className="cb-hts-js-2026-editor-block__title">{ __( 'CB Specs', 'cb-hts-js-2026' ) }</p>
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
			<TextareaControl
				label={ __( 'Intro', 'cb-hts-js-2026' ) }
				value={ intro }
				onChange={ ( value ) => setAttributes( { intro: value } ) }
				help={ __( 'Newlines become line breaks.', 'cb-hts-js-2026' ) }
			/>
			<RepeaterField
				label={ __( 'Rows', 'cb-hts-js-2026' ) }
				value={ rows }
				onChange={ ( value ) => setAttributes( { rows: value } ) }
				fields={ FIELDS }
				emptyRow={ EMPTY_ROW }
			/>
		</div>
	);
}
