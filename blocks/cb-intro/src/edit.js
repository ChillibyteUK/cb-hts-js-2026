import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { createBlock } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const BODY_BLOCKS = [ 'core/paragraph', 'core/heading' ];
const BODY_TEMPLATE = [ [ 'core/paragraph' ] ];

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, body, signature, highlights, watermark } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	const hasInnerBlocks = useSelect( ( select ) => select( 'core/block-editor' ).getBlockCount( clientId ) > 0, [ clientId ] );
	const { replaceInnerBlocks } = useDispatch( 'core/block-editor' );

	// One-off migration: copy the legacy RichText `body` attribute into
	// paragraph inner blocks the first time an older instance is opened.
	useEffect( () => {
		if ( ! body || hasInnerBlocks ) {
			return;
		}
		const paragraphs = [ ...body.matchAll( /<p[^>]*>([\s\S]*?)<\/p>/gi ) ].map( ( m ) => m[ 1 ] );
		const contents = paragraphs.length ? paragraphs : [ body ];
		replaceInnerBlocks( clientId, contents.map( ( content ) => createBlock( 'core/paragraph', { content } ) ), false );
		setAttributes( { body: '' } );
	}, [] );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Intro', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
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
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Body', 'cb-hts-js-2026' ) }</label>
				<div className="cb-hts-js-2026-editor-field__control">
					<InnerBlocks
						allowedBlocks={ BODY_BLOCKS }
						template={ BODY_TEMPLATE }
						templateLock={ false }
					/>
				</div>
				<p className="cb-hts-js-2026-editor-field__help">{ __( 'Paragraphs plus H2 / H3 headings. First paragraph renders larger as a lede.', 'cb-hts-js-2026' ) }</p>
			</div>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextareaControl
						label={ __( 'Signature', 'cb-hts-js-2026' ) }
						value={ signature }
						onChange={ ( value ) => setAttributes( { signature: value } ) }
						help={ __( 'Trust line shown below the body. Inline <strong> allowed.', 'cb-hts-js-2026' ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextareaControl
						label={ __( 'Highlights', 'cb-hts-js-2026' ) }
						value={ highlights }
						onChange={ ( value ) => setAttributes( { highlights: value } ) }
						help={ __( 'One per line.', 'cb-hts-js-2026' ) }
					/>
				</div>
			</div>
			<TextControl
				label={ __( 'Watermark', 'cb-hts-js-2026' ) }
				value={ watermark }
				onChange={ ( value ) => setAttributes( { watermark: value } ) }
				help={ __( 'Optional giant background word, e.g. INTRO. Leave empty for none.', 'cb-hts-js-2026' ) }
			/>
		</EditorBlockShell>
	);
}
