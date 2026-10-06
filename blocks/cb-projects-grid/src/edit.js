import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import PostTypePicker from '../../_shared/PostTypePicker';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, productId } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Projects Grid', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<TextControl
				label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
				value={ eyebrow }
				onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
			/>
			<TextareaControl
				label={ __( 'Headline', 'cb-hts-js-2026' ) }
				value={ headline }
				onChange={ ( value ) => setAttributes( { headline: value } ) }
				help={ __( 'Wrap emphasised text in a <span> to render it italic + orange.', 'cb-hts-js-2026' ) }
			/>
			<PostTypePicker
				label={ __( 'Filter by product used', 'cb-hts-js-2026' ) }
				postType="product"
				value={ productId }
				onChange={ ( id ) => setAttributes( { productId: id } ) }
				help={ __( 'Only show projects featuring this product (read from each project’s Product Used block). Leave empty for the 5 most recent projects.', 'cb-hts-js-2026' ) }
			/>
			<p>
				{ __(
					'Cards are pulled live from the Project post type, newest first — the first 5, in a fixed hero/wide/standard mosaic layout. Not editable here.',
					'cb-hts-js-2026'
				) }
			</p>
		</EditorBlockShell>
	);
}
