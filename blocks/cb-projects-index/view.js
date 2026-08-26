/**
 * Projects index filtering.
 *
 * Every card is rendered server side; these buttons toggle visibility by the
 * `data-cats` slug list on each card. Multiple indexes on a page are handled
 * independently. Ported from cb-hts2026's src/js/custom-javascript.js.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	var indexes = document.querySelectorAll( '.projects-index' );

	Array.prototype.forEach.call( indexes, function ( index ) {
		var buttons = index.querySelectorAll( '.projects-index-filter' );
		var cards = index.querySelectorAll( '.projects-index-card' );
		var empty = index.querySelector( '.projects-index-empty' );

		if ( ! buttons.length || ! cards.length ) {
			return;
		}

		Array.prototype.forEach.call( buttons, function ( button ) {
			button.addEventListener( 'click', function () {
				var filter = button.getAttribute( 'data-filter' );
				var visible = 0;

				Array.prototype.forEach.call( buttons, function ( other ) {
					var active = other === button;
					other.classList.toggle( 'is-active', active );
					other.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
				} );

				Array.prototype.forEach.call( cards, function ( card ) {
					var cats = ( card.getAttribute( 'data-cats' ) || '' ).split( ' ' );
					var match = filter === 'all' || cats.indexOf( filter ) !== -1;

					card.hidden = ! match;

					if ( match ) {
						visible += 1;
					}
				} );

				if ( empty ) {
					empty.hidden = visible > 0;
				}
			} );
		} );
	} );
} );
