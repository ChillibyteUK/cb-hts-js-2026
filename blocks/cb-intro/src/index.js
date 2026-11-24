import { registerBlockType, unregisterBlockVariation } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';
import { InnerBlocks } from '@wordpress/block-editor';
import Edit from './edit';
import metadata from '../block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );

// Headings are H2 / H3 only in the editor. WP 7.1's heading level picker is
// the block switcher's per-level variations (not the block's `levelOptions`
// attribute), and variations can't be scoped to a parent block, so this
// applies to every core Heading in the editor. Existing H1/H4–H6 headings
// still render; they just can't be newly picked.
domReady( () => {
	[ 'h1', 'h4', 'h5', 'h6' ].forEach( ( variation ) => unregisterBlockVariation( 'core/heading', variation ) );
} );
