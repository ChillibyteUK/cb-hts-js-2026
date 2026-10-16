import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl, CheckboxControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { decodeEntities } from '@wordpress/html-entities';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import URLInput from '../../_shared/URLInput';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, applicationId, applicationIds, buttonText, buttonUrl, buttonTarget } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	const terms = useSelect(
		( select ) => select( coreStore ).getEntityRecords( 'taxonomy', 'application_cat', { per_page: -1, orderby: 'name', order: 'asc' } ),
		[]
	);

	// Blocks saved before the checklist stored a single applicationId.
	const selected = applicationIds && applicationIds.length ? applicationIds : applicationId ? [ applicationId ] : [];

	const toggleTerm = ( id, checked ) => {
		const next = checked ? [ ...selected, id ] : selected.filter( ( value ) => value !== id );
		setAttributes( { applicationIds: next, applicationId: 0 } );
	};

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Case Studies', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
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
			<div className="cb-hts-js-2026-editor-field" role="group" aria-label={ __( 'Applications', 'cb-hts-js-2026' ) }>
				<span className="cb-hts-js-2026-editor-field__label">{ __( 'Applications', 'cb-hts-js-2026' ) }</span>
				<div className="cb-hts-js-2026-editor-checklist">
					{ ( terms || [] ).map( ( term ) => (
						<CheckboxControl
							key={ term.id }
							__nextHasNoMarginBottom
							label={ decodeEntities( term.name ) }
							checked={ selected.includes( term.id ) }
							onChange={ ( checked ) => toggleTerm( term.id, checked ) }
						/>
					) ) }
				</div>
				<p className="cb-hts-js-2026-editor-field__help">
					{ __( 'Shows projects in the ticked Application categories, newest first (up to 12). Tick none for all. More than three matches become a slider.', 'cb-hts-js-2026' ) }
				</p>
			</div>
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '50 1 0%' } }>
					<TextControl
						label={ __( 'Button Text', 'cb-hts-js-2026' ) }
						value={ buttonText }
						onChange={ ( value ) => setAttributes( { buttonText: value } ) }
					/>
				</div>
				<div style={ { flex: '50 1 0%' } }>
					<div className="cb-hts-js-2026-editor-field">
						<label className="cb-hts-js-2026-editor-field__label">{ __( 'Button URL', 'cb-hts-js-2026' ) }</label>
						<URLInput
							value={ buttonUrl || '' }
							onChange={ ( value ) => setAttributes( { buttonUrl: value } ) }
						/>
					</div>
					<ToggleControl
						label={ __( 'Open button in a new tab', 'cb-hts-js-2026' ) }
						checked={ buttonTarget }
						onChange={ ( value ) => setAttributes( { buttonTarget: value } ) }
					/>
				</div>
			</div>
		</EditorBlockShell>
	);
}
