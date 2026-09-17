import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextareaControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const FIELDS = [
	{ name: 'question', label: __( 'Question', 'cb-hts-js-2026' ), type: 'textarea' },
	{ name: 'answer', label: __( 'Answer', 'cb-hts-js-2026' ), type: 'textarea' },
];

const EMPTY_ROW = { question: '', answer: '' };

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { headline, intro, faqs } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB FAQs', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
			<TextareaControl
				label={ __( 'Title', 'cb-hts-js-2026' ) }
				value={ headline }
				onChange={ ( value ) => setAttributes( { headline: value } ) }
				help={ __( 'Wrap emphasised text in a <span> to render it italic + orange.', 'cb-hts-js-2026' ) }
			/>
			<TextareaControl
				label={ __( 'Intro', 'cb-hts-js-2026' ) }
				value={ intro }
				onChange={ ( value ) => setAttributes( { intro: value } ) }
				help={ __( 'Newlines become line breaks.', 'cb-hts-js-2026' ) }
			/>
			<RepeaterField
				label={ __( 'FAQs', 'cb-hts-js-2026' ) }
				value={ faqs }
				onChange={ ( value ) => setAttributes( { faqs: value } ) }
				fields={ FIELDS }
				emptyRow={ EMPTY_ROW }
			/>
		</EditorBlockShell>
	);
}
