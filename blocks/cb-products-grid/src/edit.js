import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const PRODUCT_FIELDS = [
	{ name: 'productId', label: __( 'Product', 'cb-hts-js-2026' ), type: 'post', postType: 'product' },
];
const EMPTY_PRODUCT = { productId: 0 };

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, lede, productIds } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="CB Products Grid" textDomain="cb-hts-js-2026">
			<TextControl
				label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
				value={ eyebrow }
				onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
			/>
			<TextareaControl
				label={ __( 'Headline', 'cb-hts-js-2026' ) }
				value={ headline }
				onChange={ ( value ) => setAttributes( { headline: value } ) }
				help={ __( 'Wrap emphasised text in <span> to render it italic + orange.', 'cb-hts-js-2026' ) }
			/>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Lede', 'cb-hts-js-2026' ) }</label>
				<RichText
					tagName="div"
					multiline="p"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Lede', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Lede', 'cb-hts-js-2026' ) }
					value={ lede }
					onChange={ ( value ) => setAttributes( { lede: value } ) }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link', 'cb-hts-js-2026/small-text' ] }
				/>
			</div>
			<RepeaterField
				label={ __( 'Products', 'cb-hts-js-2026' ) }
				value={ productIds }
				onChange={ ( value ) => setAttributes( { productIds: value } ) }
				fields={ PRODUCT_FIELDS }
				emptyRow={ EMPTY_PRODUCT }
			/>
			<p>
				{ __( 'Add products to choose which are shown, in this order. Leave empty to show every product (menu order, then date).', 'cb-hts-js-2026' ) }
			</p>
		</EditorBlockShell>
	);
}
