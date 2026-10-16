import { URLInput as CoreURLInput } from '@wordpress/block-editor';
import { useRef, useState } from '@wordpress/element';

/**
 * Drop-in replacement for core's URLInput that closes its suggestions when
 * focus leaves.
 *
 * Core's URLInput never hides its suggestion list on blur — it expects to
 * live inside a popover that closes as a whole. Here it sits directly in
 * the block canvas (an iframe) while the suggestions render in a popover in
 * the parent document, so clicking away left the list floating open.
 *
 * Closing is done by remounting the core input (its `showSuggestions` state
 * is internal and has no prop to reset it). The value is controlled by the
 * caller, so nothing is lost. A short delay lets a click on a suggestion
 * register first: that popover lives in the parent document, so it is never
 * a related blur target from inside the iframe.
 */
export default function URLInput( props ) {
	const [ resetKey, setResetKey ] = useState( 0 );
	const wrapperRef = useRef();

	const onBlur = () => {
		window.setTimeout( () => {
			const wrapper = wrapperRef.current;
			if ( ! wrapper ) {
				return;
			}
			const stillInside = wrapper.contains( wrapper.ownerDocument.activeElement );
			const inSuggestions = document.activeElement?.closest?.( '.block-editor-url-input__suggestions' );
			if ( ! stillInside && ! inSuggestions ) {
				setResetKey( ( key ) => key + 1 );
			}
		}, 150 );
	};

	return (
		<div ref={ wrapperRef } onBlur={ onBlur }>
			<CoreURLInput key={ resetKey } { ...props } />
		</div>
	);
}
