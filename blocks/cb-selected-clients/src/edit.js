import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { ToggleControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { fullWidth } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Selected Clients', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<p>
				{ __(
					'Client logos are managed globally in Site-Wide Settings → Clients, not per-block.',
					'cb-hts-js-2026'
				) }
			</p>
			<ToggleControl
				label={ __( 'Full width', 'cb-hts-js-2026' ) }
				checked={ fullWidth }
				onChange={ ( value ) => setAttributes( { fullWidth: value } ) }
				help={ __( 'Runs the logos edge to edge instead of inside the page container, and hides the rotated title.', 'cb-hts-js-2026' ) }
			/>
		</EditorBlockShell>
	);
}
