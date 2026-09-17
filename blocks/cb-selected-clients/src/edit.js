import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { clientId } ) {
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Selected Clients', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<p>
				{ __(
					'Client logos are managed globally in Site-Wide Settings → Clients, not per-block.',
					'cb-hts-js-2026'
				) }
			</p>
		</EditorBlockShell>
	);
}
