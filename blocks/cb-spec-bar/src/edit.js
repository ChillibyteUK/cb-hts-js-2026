import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const specItemsFields = [
	{ name: 'label', label: __( 'Label', 'cb-hts-js-2026' ), type: 'text' },
	{ name: 'value', label: __( 'Value', 'cb-hts-js-2026' ), type: 'text' },
	{ name: 'unit', label: __( 'Unit', 'cb-hts-js-2026' ), type: 'text' },
];

const specItemsEmptyRow = { label: '', value: '', unit: '' };

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { specItems } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="CB Spec Bar" textDomain="cb-hts-js-2026">
			<RepeaterField
				label={ __( 'Spec Items', 'cb-hts-js-2026' ) }
				value={ specItems }
				onChange={ ( value ) => setAttributes( { specItems: value } ) }
				fields={ specItemsFields }
				emptyRow={ specItemsEmptyRow }
			/>
		</EditorBlockShell>
	);
}
