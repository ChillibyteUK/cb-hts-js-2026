import { registerBlockType } from '@wordpress/blocks';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { useSelect } from '@wordpress/data';
import { InnerBlocks } from '@wordpress/block-editor';
import Edit from './edit';
import metadata from '../block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );

// Limit headings inside CB Intro's body to H2 / H3 (core/heading's
// `levelOptions` drives its toolbar level picker).
addFilter(
	'editor.BlockEdit',
	'cb-hts-js-2026/cb-intro-heading-levels',
	createHigherOrderComponent( ( BlockEdit ) => ( props ) => {
		const insideIntro = useSelect(
			( select ) => 'core/heading' === props.name && select( 'core/block-editor' ).getBlockParentsByBlockName( props.clientId, metadata.name ).length > 0,
			[ props.clientId, props.name ]
		);
		if ( ! insideIntro ) {
			return <BlockEdit { ...props } />;
		}
		return <BlockEdit { ...props } attributes={ { ...props.attributes, levelOptions: [ 2, 3 ] } } />;
	}, 'withCbIntroHeadingLevels' )
);
