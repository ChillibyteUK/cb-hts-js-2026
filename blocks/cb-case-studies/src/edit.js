import { __ } from '@wordpress/i18n';
import { useBlockProps, URLInput } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl, SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { decodeEntities } from '@wordpress/html-entities';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, applicationId, limit, buttonText, buttonUrl, buttonTarget } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	const terms = useSelect(
		( select ) => select( coreStore ).getEntityRecords( 'taxonomy', 'application_cat', { per_page: -1, orderby: 'name', order: 'asc' } ),
		[]
	);

	const options = [
		{ value: 0, label: __( 'All applications', 'cb-hts-js-2026' ) },
		...( terms || [] ).map( ( term ) => ( { value: term.id, label: decodeEntities( term.name ) } ) ),
	];

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
			<div style={ { display: 'flex', flexWrap: 'wrap', gap: '12px' } }>
				<div style={ { flex: '75 1 0%' } }>
					<SelectControl
						label={ __( 'Application', 'cb-hts-js-2026' ) }
						value={ applicationId }
						options={ options }
						onChange={ ( value ) => setAttributes( { applicationId: Number( value ) } ) }
						help={ __( 'Shows projects in this Application category, newest first.', 'cb-hts-js-2026' ) }
					/>
				</div>
				<div style={ { flex: '25 1 0%' } }>
					<TextControl
						type="number"
						min="1"
						max="3"
						label={ __( 'Max projects', 'cb-hts-js-2026' ) }
						value={ limit }
						onChange={ ( value ) => setAttributes( { limit: Math.min( 3, Math.max( 1, Number( value ) || 3 ) ) } ) }
					/>
				</div>
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
