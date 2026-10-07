import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { TextControl, Button } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

/**
 * "Add from URL" companion for MediaUpload image pickers — the same
 * server-side sideload behind core Image block's own "Upload to Media
 * Library" button (POST /wp/v2/media with a url; core fetches the file
 * itself, so no browser CORS issue, with SSRF/size guards and the normal
 * upload_files capability check server-side).
 *
 * Exists because MediaUpload only picks from the library: pasting an
 * external image URL otherwise hotlinks it forever. This downloads it into
 * the library first, then hands back a MediaUpload-shaped object so call
 * sites reuse their existing onSelect verbatim.
 *
 * @param {Object}   props
 * @param {Function} props.onSelect ( media: { id: number, url: string, alt: string } ) => void
 */
export default function SideloadImage( { onSelect } ) {
	const [ url, setUrl ] = useState( '' );
	const [ isBusy, setIsBusy ] = useState( false );
	const [ error, setError ] = useState( '' );

	async function addFromUrl() {
		const trimmed = ( url || '' ).trim();

		if ( '' === trimmed ) {
			return;
		}

		setIsBusy( true );
		setError( '' );

		try {
			const attachment = await apiFetch( {
				path: '/wp/v2/media',
				method: 'POST',
				data: { url: trimmed },
			} );

			onSelect( {
				id: attachment.id,
				url: attachment.source_url,
				alt: attachment.alt_text || '',
			} );
			setUrl( '' );
		} catch ( err ) {
			setError( err?.message || __( 'Could not download that image.', 'cb-hts-js-2026' ) );
		} finally {
			setIsBusy( false );
		}
	}

	return (
		<div className="cb-hts-js-2026-sideload">
			<TextControl
				type="url"
				label={ __( 'Or add from URL', 'cb-hts-js-2026' ) }
				value={ url }
				onChange={ setUrl }
				help={ __( 'Paste an image link — it is saved into the Media Library first, never hotlinked.', 'cb-hts-js-2026' ) }
			/>
			<Button variant="secondary" size="small" isBusy={ isBusy } disabled={ isBusy || '' === url.trim() } onClick={ addFromUrl }>
				{ __( 'Add to library', 'cb-hts-js-2026' ) }
			</Button>
			{ error && (
				<p className="cb-hts-js-2026-sideload__error" role="alert">
					{ error }
				</p>
			) }
		</div>
	);
}
