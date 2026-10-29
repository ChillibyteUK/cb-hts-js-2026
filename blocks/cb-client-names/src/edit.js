import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const FIELDS = [
	{ name: 'name', label: __( 'Client name', 'cb-hts-js-2026' ), type: 'text' },
];

const EMPTY_ROW = { name: '' };

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { label, names } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Client Names', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<TextControl
				label={ __( 'Rotated title', 'cb-hts-js-2026' ) }
				value={ label }
				onChange={ ( value ) => setAttributes( { label: value } ) }
			/>
			<RepeaterField
				label={ __( 'Names', 'cb-hts-js-2026' ) }
				value={ names }
				onChange={ ( value ) => setAttributes( { names: value } ) }
				fields={ FIELDS }
				emptyRow={ EMPTY_ROW }
			/>
		</EditorBlockShell>
	);
}
