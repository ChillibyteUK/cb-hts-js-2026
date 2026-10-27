import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import PostTypePicker from '../../_shared/PostTypePicker';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import ImagePicker from '../../_shared/ImagePicker';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { productId, eyebrow, heading, summary, imageId, imageUrl, imageAlt } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Product Used', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<PostTypePicker
				label={ __( 'Product', 'cb-hts-js-2026' ) }
				postType="product"
				value={ productId }
				onChange={ ( id ) => setAttributes( { productId: id } ) }
				help={ __( 'The product featured in this case study. Title, excerpt and featured image are pulled from it unless overridden below.', 'cb-hts-js-2026' ) }
			/>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
						value={ eyebrow }
						onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
						help={ __( 'Defaults to "Product used in this case study".', 'cb-hts-js-2026' ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Heading', 'cb-hts-js-2026' ) }
						value={ heading }
						onChange={ ( value ) => setAttributes( { heading: value } ) }
						help={ __( 'Defaults to the product title.', 'cb-hts-js-2026' ) }
					/>
				</div>
			</div>
			<TextareaControl
				label={ __( 'Summary', 'cb-hts-js-2026' ) }
				value={ summary }
				onChange={ ( value ) => setAttributes( { summary: value } ) }
				help={ __( 'Defaults to the product excerpt.', 'cb-hts-js-2026' ) }
			/>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Image', 'cb-hts-js-2026' ) }</label>
				<ImagePicker
					id={ imageId }
					url={ imageUrl }
					onSelect={ ( media ) =>
						setAttributes( {
							imageId: media.id,
							imageUrl: media.url,
							imageAlt: media.alt || '',
						} )
					}
					onRemove={ () => setAttributes( { imageId: 0, imageUrl: '', imageAlt: '' } ) }
				/>
				<p className="cb-hts-js-2026-editor-field__help">{ __( 'Defaults to the product featured image.', 'cb-hts-js-2026' ) }</p>
			</div>
		</EditorBlockShell>
	);
}
