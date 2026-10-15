import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import RepeaterField from '../../_shared/RepeaterField';
import EditorBlockShell from '../../_shared/EditorBlockShell';

const ITEM_FIELDS = [
	{ name: 'title', label: __( 'Title', 'cb-hts-js-2026' ), type: 'text' },
	{ name: 'content', label: __( 'Content', 'cb-hts-js-2026' ), type: 'richtext', multiline: true },
];
const EMPTY_ITEM = { title: '', content: '' };

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { eyebrow, headline, items } = attributes;
	const blockProps = useBlockProps( { className: 'container cb-hts-js-2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title={ __( 'CB Feature Grid', 'cb-hts-js-2026' ) } textDomain="cb-hts-js-2026">
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
			<RepeaterField
				label={ __( 'Items', 'cb-hts-js-2026' ) }
				value={ items }
				onChange={ ( value ) => setAttributes( { items: value } ) }
				fields={ ITEM_FIELDS }
				emptyRow={ EMPTY_ITEM }
			/>
		</EditorBlockShell>
	);
}
