import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, ToggleControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const factsFields = [
	{ name: 'label', label: __( 'Label', 'cb-hts-js-2026' ), type: 'text' },
	{ name: 'value', label: __( 'Value', 'cb-hts-js-2026' ), type: 'textarea' },
	{ name: 'link', label: __( 'Link', 'cb-hts-js-2026' ), type: 'link', linkTarget: true },
];

const factsEmptyRow = { label: '', value: '', link: '', linkText: '', linkTarget: false };

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { facts, ctaText, ctaUrl, ctaTarget } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="CB Case Study Sidebar" textDomain="cb-hts-js-2026">
			<RepeaterField
				label={ __( 'Facts', 'cb-hts-js-2026' ) }
				value={ facts }
				onChange={ ( value ) => setAttributes( { facts: value } ) }
				fields={ factsFields }
				emptyRow={ factsEmptyRow }
				layout="column"
			/>
			<TextControl
				label={ __( 'CTA Text', 'cb-hts-js-2026' ) }
				value={ ctaText }
				onChange={ ( value ) => setAttributes( { ctaText: value } ) }
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
		</EditorBlockShell>
	);
}
