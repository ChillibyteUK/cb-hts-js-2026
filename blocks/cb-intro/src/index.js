import { registerBlockType, unregisterBlockVariation, store as blocksStore } from '@wordpress/blocks';
import { subscribe, select } from '@wordpress/data';
import { InnerBlocks } from '@wordpress/block-editor';
import Edit from './edit';
import metadata from '../block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );

// Headings are H2 / H3 only in the editor. WP 7.1's heading level picker is
// the block switcher's / slash inserter's per-level variations (not the
// block's `levelOptions` attribute), and variations can't be scoped to a
// parent block, so this applies to every core Heading in the editor.
// Existing H1/H4–H6 headings still render; they just can't be newly picked.
// Core headings register after this script runs, so wait for them rather
// than unregistering up front (which silently no-ops).
const BLOCKED_HEADING_VARIATIONS = [ 'h1', 'h4', 'h5', 'h6' ];

const unsubscribe = subscribe( () => {
	const variations = select( blocksStore ).getBlockVariations( 'core/heading' );
	if ( ! variations || ! variations.length ) {
		return;
	}
	unsubscribe();
	BLOCKED_HEADING_VARIATIONS.forEach( ( name ) => unregisterBlockVariation( 'core/heading', name ) );
} );
