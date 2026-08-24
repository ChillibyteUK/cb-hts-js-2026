import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';

const STAT_FIELDS = [
	{ name: 'value', label: __( 'Value', 'cb-hts-js-2026' ), type: 'text', help: __( 'Supports <sup> for suffixes and values like <14.', 'cb-hts-js-2026' ) },
	{ name: 'label', label: __( 'Label', 'cb-hts-js-2026' ), type: 'textarea' },
];
const EMPTY_STAT = { value: '', label: '' };

const REASON_FIELDS = [
	{ name: 'title', label: __( 'Title', 'cb-hts-js-2026' ), type: 'text' },
	{ name: 'body', label: __( 'Body', 'cb-hts-js-2026' ), type: 'textarea' },
];
const EMPTY_REASON = { title: '', body: '' };

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, headline, body, stats, reasons } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<div { ...blockProps }>
			<p className="cb-hts-js-2026-editor-block__title">{ __( 'CB Why Split', 'cb-hts-js-2026' ) }</p>
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
				label={ __( 'Body', 'cb-hts-js-2026' ) }
				value={ body }
				onChange={ ( value ) => setAttributes( { body: value } ) }
				help={ __( 'Newlines become line breaks.', 'cb-hts-js-2026' ) }
			/>
			<RepeaterField
				label={ __( 'Stats', 'cb-hts-js-2026' ) }
				value={ stats }
				onChange={ ( value ) => setAttributes( { stats: value } ) }
				fields={ STAT_FIELDS }
				emptyRow={ EMPTY_STAT }
			/>
			<RepeaterField
				label={ __( 'Reasons', 'cb-hts-js-2026' ) }
				value={ reasons }
				onChange={ ( value ) => setAttributes( { reasons: value } ) }
				fields={ REASON_FIELDS }
				emptyRow={ EMPTY_REASON }
			/>
		</div>
	);
}
