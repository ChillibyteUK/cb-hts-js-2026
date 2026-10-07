import { __ } from '@wordpress/i18n';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * "Small text" inline format — a toolbar button in the RichText selection
 * popover (same place Bold/Italic/Link live), not a block attribute. A
 * block-level control can only size a whole RichText field, never one
 * paragraph within it — so per-paragraph sizing needs per-selection
 * granularity, which is exactly what a format expresses.
 *
 * Applies the theme's own .text-small class to the selection, so it renders
 * identically in the editor (theme.min.css loads there) and on the frontend
 * with no extra CSS anywhere. Blocks opt their RichText fields in by adding
 * 'cb-hts-js-2026/small-text' to allowedFormats.
 *
 * Registered once, globally, via enqueue_block_editor_assets (see
 * inc/editor.php) rather than per-block — same reasoning the shared
 * RepeaterField is one component rather than duplicated per block. Not
 * auto-registered by inc/blocks.php's blocks/*block.json glob (this folder
 * has no block.json — it's a format, not a block).
 */
const FORMAT_NAME = 'cb-hts-js-2026/small-text';

registerFormatType( FORMAT_NAME, {
	title: __( 'Small text', 'cb-hts-js-2026' ),
	tagName: 'span',
	className: 'text-small',
	edit( { isActive, value, onChange } ) {
		return (
			<RichTextToolbarButton
				icon={
					<svg viewBox="0 0 20 20" aria-hidden="true">
						<text x="10" y="14.5" textAnchor="middle" fontSize="11" fontWeight="700" fill="currentColor">A</text>
					</svg>
				}
				title={ __( 'Small text', 'cb-hts-js-2026' ) }
				onClick={ () => onChange( toggleFormat( value, { type: FORMAT_NAME } ) ) }
				isActive={ isActive }
			/>
		);
	},
} );
