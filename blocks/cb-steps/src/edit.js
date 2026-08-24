import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';

/**
 * Step number/icon/title are fixed (see render.php's STEP_DEFAULTS) — only
 * body copy is editable, matching the source's ACF repeater (min=5/max=5,
 * one hardcoded title per row). The shared RepeaterField component
 * (blocks/_shared/RepeaterField.js) is deliberately NOT used here: its
 * add/move/remove UI assumes rows are free-form and interchangeable, but
 * here row 0 IS "Free Consultation" and always must be — letting an editor
 * reorder or delete a row would desync the body text from the fixed
 * icon/title it's meant to belong to. Five plain fields, labelled with
 * their fixed step title, is the correct shape for this data, not a
 * generalisation of the repeater pattern.
 */
const STEP_TITLES = [
	'Free Consultation',
	'Site Survey',
	'Design & Quote',
	'Manufacture & Deliver',
	'Install & Handover',
];

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, headline, intro, steps } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	function updateStepBody( index, body ) {
		const next = steps.slice();
		next[ index ] = { ...next[ index ], body };
		setAttributes( { steps: next } );
	}

	return (
		<div { ...blockProps }>
			<p className="cb-hts-js-2026-editor-block__title">CB Steps</p>
			<TextControl
				label={ __( 'Eyebrow', 'cb-hts-js-2026' ) }
				value={ eyebrow }
				onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
			/>
			<TextareaControl
				label={ __( 'Headline', 'cb-hts-js-2026' ) }
				value={ headline }
				onChange={ ( value ) => setAttributes( { headline: value } ) }
				help={ __( 'Wrap highlighted text in <span>.', 'cb-hts-js-2026' ) }
			/>
			<TextareaControl
				label={ __( 'Intro', 'cb-hts-js-2026' ) }
				value={ intro }
				onChange={ ( value ) => setAttributes( { intro: value } ) }
			/>
			<p className="cb-hts-js-2026-editor-field__label">{ __( 'Step Bodies', 'cb-hts-js-2026' ) }</p>
			{ STEP_TITLES.map( ( title, index ) => (
				<TextareaControl
					key={ index }
					label={ `${ __( 'Step', 'cb-hts-js-2026' ) } ${ index + 1 }: ${ title }` }
					value={ ( steps[ index ] && steps[ index ].body ) || '' }
					onChange={ ( value ) => updateStepBody( index, value ) }
				/>
			) ) }
		</div>
	);
}
