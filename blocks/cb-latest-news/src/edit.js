import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import URLInput from '../../_shared/URLInput';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { headline, buttonText, buttonUrl, buttonTarget } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Latest News', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<TextareaControl
				label={ __( 'Title', 'cb-hts-js-2026' ) }
				value={ headline }
				onChange={ ( value ) => setAttributes( { headline: value } ) }
				help={ __( 'Wrap emphasised text in a <span> to render it italic + orange.', 'cb-hts-js-2026' ) }
			/>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Button Text', 'cb-hts-js-2026' ) }
						value={ buttonText }
						onChange={ ( value ) => setAttributes( { buttonText: value } ) }
						help={ __( 'Defaults to "See all".', 'cb-hts-js-2026' ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<div className="cb-hts-js-2026-editor-field">
						<label className="cb-hts-js-2026-editor-field__label">{ __( 'Button URL', 'cb-hts-js-2026' ) }</label>
						<URLInput
							value={ buttonUrl || '' }
							onChange={ ( value ) => setAttributes( { buttonUrl: value } ) }
						/>
						<p className="cb-hts-js-2026-editor-field__help">
							{ __( 'Defaults to the Insights (posts) page.', 'cb-hts-js-2026' ) }
						</p>
					</div>
					<ToggleControl
						label={ __( 'Open button in a new tab', 'cb-hts-js-2026' ) }
						checked={ buttonTarget }
						onChange={ ( value ) => setAttributes( { buttonTarget: value } ) }
					/>
				</div>
			</div>
			<p>
				{ __( 'Shows the three most recent Insights posts. On mobile they become a slider.', 'cb-hts-js-2026' ) }
			</p>
		</EditorBlockShell>
	);
}
