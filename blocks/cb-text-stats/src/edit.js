import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl, SelectControl, ToggleControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const STAT_FIELDS = [
	{ name: 'value', label: __( 'Value', 'cb-hts-js-2026' ), type: 'text', help: __( 'Supports <sup> for suffixes.', 'cb-hts-js-2026' ) },
	{ name: 'label', label: __( 'Label', 'cb-hts-js-2026' ), type: 'textarea' },
];
const EMPTY_STAT = { value: '', label: '' };

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { variant, eyebrow, headline, body, linkText, linkUrl, linkTarget, stats } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="CB Text Stats" textDomain="cb-hts-js-2026">
			<SelectControl
				label={ __( 'Variant', 'cb-hts-js-2026' ) }
				value={ variant }
				options={ [
					{ label: 'Navy band', value: 'dark' },
					{ label: 'Pull-quote on paper', value: 'quote' },
				] }
				onChange={ ( value ) => setAttributes( { variant: value } ) }
				help={ __( 'Navy band is the standard section. Pull-quote renders the headline as a large quote on paper with the stats in a grid — the outcome band on a case study.', 'cb-hts-js-2026' ) }
			/>
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
			<TextControl
				label={ __( 'Link Text', 'cb-hts-js-2026' ) }
				value={ linkText }
				onChange={ ( value ) => setAttributes( { linkText: value } ) }
			/>
			<TextControl
				type="url"
				label={ __( 'Link URL', 'cb-hts-js-2026' ) }
				value={ linkUrl }
				onChange={ ( value ) => setAttributes( { linkUrl: value } ) }
			/>
			<ToggleControl
				label={ __( 'Open Link in a new tab', 'cb-hts-js-2026' ) }
				checked={ linkTarget }
				onChange={ ( value ) => setAttributes( { linkTarget: value } ) }
			/>
			<RepeaterField
				label={ __( 'Stats', 'cb-hts-js-2026' ) }
				value={ stats }
				onChange={ ( value ) => setAttributes( { stats: value } ) }
				fields={ STAT_FIELDS }
				emptyRow={ EMPTY_STAT }
			/>
		</EditorBlockShell>
	);
}
