import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, SelectControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { heading, level, size } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="CB Heading" textDomain="cb-hts-js-2026">
			<TextControl
				label={ __( 'Heading', 'cb-hts-js-2026' ) }
				value={ heading }
				onChange={ ( value ) => setAttributes( { heading: value } ) }
			/>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
			<SelectControl
				label={ __( 'Level', 'cb-hts-js-2026' ) }
				value={ level }
				options={ [
					{ label: '', value: '' },
					{ label: 'H1', value: 'H1' },
					{ label: 'H2', value: 'H2' },
					{ label: 'H3', value: 'H3' },
					{ label: 'H4', value: 'H4' },
					{ label: 'H5', value: 'H5' },
					{ label: 'H6', value: 'H6' },
				] }
				onChange={ ( value ) => setAttributes( { level: value } ) }
				help={ __( 'Semantic level', 'cb-hts-js-2026' ) }
			/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
			<SelectControl
				label={ __( 'Size', 'cb-hts-js-2026' ) }
				value={ size }
				options={ [
					{ label: '', value: '' },
					{ label: 'H1', value: 'H1' },
					{ label: 'H2', value: 'H2' },
					{ label: 'H3', value: 'H3' },
					{ label: 'H4', value: 'H4' },
					{ label: 'H5', value: 'H5' },
					{ label: 'H6', value: 'H6' },
				] }
				onChange={ ( value ) => setAttributes( { size: value } ) }
				help={ __( 'Visual size', 'cb-hts-js-2026' ) }
			/>
				</div>
			</div>
		</EditorBlockShell>
	);
}
