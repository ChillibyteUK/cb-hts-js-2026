import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, headline, intro, showFilters, postsPerPage } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<div { ...blockProps }>
			<p className="cb-hts-js-2026-editor-block__title">{ __( 'CB Projects Index', 'cb-hts-js-2026' ) }</p>
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
				<label className="cb-hts-js-2026-editor-field__label">{ __( 'Intro', 'cb-hts-js-2026' ) }</label>
				<RichText
					tagName="div"
					multiline="p"
					className="cb-hts-js-2026-editor-field__control"
					aria-label={ __( 'Intro', 'cb-hts-js-2026' ) }
					placeholder={ __( 'Intro', 'cb-hts-js-2026' ) }
					value={ intro }
					onChange={ ( value ) => setAttributes( { intro: value } ) }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
				/>
				<p className="cb-hts-js-2026-editor-field__help">{ __( 'Optional short standfirst, shown beside the headline.', 'cb-hts-js-2026' ) }</p>
			</div>
			<ToggleControl
				label={ __( 'Show filter bar', 'cb-hts-js-2026' ) }
				checked={ showFilters }
				onChange={ ( value ) => setAttributes( { showFilters: value } ) }
				help={ __( 'Filter buttons are built from the Project Category terms that are actually in use.', 'cb-hts-js-2026' ) }
			/>
			<TextControl
				type="number"
				label={ __( 'Maximum projects', 'cb-hts-js-2026' ) }
				value={ postsPerPage }
				onChange={ ( value ) => setAttributes( { postsPerPage: parseInt( value, 10 ) || 0 } ) }
				help={ __( 'Leave empty or 0 to show every project.', 'cb-hts-js-2026' ) }
			/>
		</div>
	);
}
