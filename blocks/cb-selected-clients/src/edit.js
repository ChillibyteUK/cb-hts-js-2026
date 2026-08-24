import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<div { ...blockProps }>
			<p className="cb-hts-js-2026-editor-block__title">{ __( 'CB Selected Clients', 'cb-hts-js-2026' ) }</p>
			<p>
				{ __(
					'Client logos are managed globally in Site-Wide Settings → Clients, not per-block.',
					'cb-hts-js-2026'
				) }
			</p>
		</div>
	);
}
