/**
 * Continuous horizontal ticker for CB Marquee Stats — duplicates the track's
 * content once so the loop can seamlessly restart, then tweens it with GSAP.
 * Runs once per page load and handles every [data-marquee] instance found.
 *
 * @package cb-hts-js-2026
 */
( function () {
	function initMarquees() {
		if ( typeof gsap === 'undefined' ) {
			return;
		}

		var prefersReduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		var marquees = document.querySelectorAll( '[data-marquee]' );

		marquees.forEach( function ( el ) {
			if ( el.dataset.marqueeReady === '1' ) {
				return;
			}
			el.dataset.marqueeReady = '1';

			var track = el.querySelector( '[data-marquee-track]' );
			if ( ! track ) {
				return;
			}

			// Duplicate items once so the second half can replace the first seamlessly.
			track.innerHTML = track.innerHTML + track.innerHTML;

			if ( prefersReduced ) {
				return;
			}

			var speed = parseFloat( el.dataset.marqueeSpeed ) || 80; // pixels per second
			var tween = null;

			function start() {
				var distance = track.scrollWidth / 2;
				if ( ! distance ) {
					return;
				}
				if ( tween ) {
					tween.kill();
				}
				gsap.set( track, { x: 0 } );
				tween = gsap.to( track, {
					x: -distance,
					duration: distance / speed,
					ease: 'none',
					repeat: -1,
				} );
			}

			start();

			var resizeTimer;
			window.addEventListener( 'resize', function () {
				clearTimeout( resizeTimer );
				resizeTimer = setTimeout( start, 150 );
			} );

			el.addEventListener( 'mouseenter', function () {
				if ( tween ) {
					gsap.to( tween, { timeScale: 0, duration: 0.3, overwrite: true } );
				}
			} );
			el.addEventListener( 'mouseleave', function () {
				if ( tween ) {
					gsap.to( tween, { timeScale: 1, duration: 0.3, overwrite: true } );
				}
			} );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initMarquees );
	} else {
		initMarquees();
	}
} )();
