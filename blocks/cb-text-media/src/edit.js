import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl, RadioControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import ImagePicker from '../../_shared/ImagePicker';
import SiteUrlInput from '../../_shared/SiteUrlInput';

// Every RichText needs its own `identifier` when a block has more than one:
// without it the fields share a selection identity and the caret/focus jumps
// between them on each keystroke (see also RepeaterField.js).
const RICH_FORMATS = [ 'core/bold', 'core/italic', 'core/link', 'cb-hts-js-2026/small-text' ];

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, lede, content, ctaText, ctaUrl, ctaTarget, imageId, imageUrl, imageAlt, order, mobileOrder } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	const setImage = ( media ) =>
		setAttributes( {
			imageId: media.id,
			imageUrl: media.url,
			imageAlt: media.alt || '',
		} );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Text Media', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<RadioControl
						className="cb-hts-js-2026-radio-horizontal"
						label={ __( 'Column order', 'cb-hts-js-2026' ) }
						selected={ order }
						options={ [
							{ label: __( 'Media | Text', 'cb-hts-js-2026' ), value: 'media-text' },
							{ label: __( 'Text | Media', 'cb-hts-js-2026' ), value: 'text-media' },
						] }
						onChange={ ( value ) => setAttributes( { order: value } ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<RadioControl
						className="cb-hts-js-2026-radio-horizontal"
						label={ __( 'Mobile order', 'cb-hts-js-2026' ) }
						selected={ mobileOrder }
						options={ [
							{ label: __( 'Media first', 'cb-hts-js-2026' ), value: 'media-text' },
							{ label: __( 'Text first', 'cb-hts-js-2026' ), value: 'text-media' },
						] }
						onChange={ ( value ) => setAttributes( { mobileOrder: value } ) }
						help={ __( 'Stacking order on small screens.', 'cb-hts-js-2026' ) }
					/>
				</div>
			</div>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Image', 'cb-hts-js-2026' ) }</label>
				<ImagePicker
					id={ imageId }
					url={ imageUrl }
					onSelect={ setImage }
					onRemove={ () => setAttributes( { imageId: 0, imageUrl: '', imageAlt: '' } ) }
				/>
				<p className="cb-hts-js-2026-editor-field__help">
					{ __( 'Stays in view (sticky) while the text scrolls on large screens.', 'cb-hts-js-2026' ) }
				</p>
			</div>
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
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Lede', 'cb-hts-js-2026' ) }</label>
				<RichText
					identifier="lede"
					tagName="div"
					multiline="p"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Lede', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Lede', 'cb-hts-js-2026' ) }
					value={ lede }
					onChange={ ( value ) => setAttributes( { lede: value } ) }
					allowedFormats={ RICH_FORMATS }
				/>
			</div>
			<div className="cb-hts-js-2026-editor-field">
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Content', 'cb-hts-js-2026' ) }</label>
				<RichText
					identifier="content"
					tagName="div"
					multiline="p"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Content', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Content', 'cb-hts-js-2026' ) }
					value={ content }
					onChange={ ( value ) => setAttributes( { content: value } ) }
					allowedFormats={ RICH_FORMATS }
				/>
			</div>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'CTA Text', 'cb-hts-js-2026' ) }
						value={ ctaText }
						onChange={ ( value ) => setAttributes( { ctaText: value } ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<div className="cb-hts-js-2026-editor-field">
						<label className="cb-hts-js-2026-editor-field__label">{ __( 'CTA URL', 'cb-hts-js-2026' ) }</label>
						<SiteUrlInput
							value={ ctaUrl || '' }
							onChange={ ( value ) => setAttributes( { ctaUrl: value } ) }
						/>
					</div>
					<ToggleControl
						label={ __( 'Open CTA in a new tab', 'cb-hts-js-2026' ) }
						checked={ ctaTarget }
						onChange={ ( value ) => setAttributes( { ctaTarget: value } ) }
					/>
				</div>
			</div>
		</EditorBlockShell>
	);
}
