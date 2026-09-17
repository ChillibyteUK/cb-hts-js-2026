import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const FIELDS = [
	{ name: 'stat', label: __( 'Stat', 'cb-hts-js-2026' ), type: 'text' },
	{ name: 'title', label: __( 'Title', 'cb-hts-js-2026' ), type: 'text' },
];

const EMPTY_ROW = { stat: '', title: '' };

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { items } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Marquee Stats', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<RepeaterField
				label={ __( 'Items', 'cb-hts-js-2026' ) }
				value={ items }
				onChange={ ( value ) => setAttributes( { items: value } ) }
				fields={ FIELDS }
				emptyRow={ EMPTY_ROW }
			/>
		</EditorBlockShell>
	);
}
