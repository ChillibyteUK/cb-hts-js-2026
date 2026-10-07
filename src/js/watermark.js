/**
 * Clamped watermark parallax + settle for section background words.
 *
 * Any <section data-watermark> containing a .{block}-watermark > span word
 * gets two behaviours, both driven here so every watermark block shares one
 * implementation instead of per-instance inline scripts:
 *
 * 1. Settle (adds .is-in-view once, via IntersectionObserver) — the CSS
 *    fades/scales the word in. Same pattern the old inline scripts used.
 * 2. Travel (scroll listener) — translates the watermark div so the word
 *    tracks the viewport like position:sticky did, but clamped to
 *    [0, sectionHeight - wordHeight]: the word can never cross its
 *    section's bounds, which is the bleed sticky caused (seen live when an
 *    intro WHAT painted over the section below). The word is a real <span>
 *    (not a ::before) precisely so its height is measurable — clamping
 *    against an estimate would reintroduce the same bug at odd font sizes.
 *
 * Bails entirely under prefers-reduced-motion (word stays put, faint).
 */
export function initWatermarks() {
	const sections = document.querySelectorAll( 'section[data-watermark]' );
	if ( ! sections.length ) return;
	if (
		!( 'IntersectionObserver' in window ) ||
		window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches
	) {
		return;
	}

	// Viewport offset the word tracks, mirroring the old sticky top.
	const navHeight =
		parseFloat(
			getComputedStyle( document.documentElement ).getPropertyValue( '--nav-height' )
		) * parseFloat( getComputedStyle( document.documentElement ).fontSize ) || 0;

	const items = [];
	sections.forEach( ( section ) => {
		const mover = section.querySelector( ':scope > [class$="-watermark"]' );
		const word = mover ? mover.querySelector( ':scope > span' ) : null;
		if ( ! mover || ! word ) return;
		items.push( { section, mover, word } );
	} );

	if ( ! items.length ) return;

	function update() {
		const viewportHeight = window.innerHeight;

		items.forEach( ( { section, mover, word } ) => {
			const rect = section.getBoundingClientRect();

			if ( rect.bottom <= 0 || rect.top >= viewportHeight ) return;

			const maxTravel = Math.max( 0, section.offsetHeight - word.offsetHeight );
			const target = Math.min( Math.max( navHeight - rect.top, 0 ), maxTravel );

			mover.style.transform = `translate3d(0, ${ target.toFixed( 1 ) }px, 0)`;
		} );

		ticking = false;
	}

	let ticking = false;

	function onScroll() {
		if ( ! ticking ) {
			window.requestAnimationFrame( update );
			ticking = true;
		}
	}

	const observer = new IntersectionObserver(
		( entries ) => {
			entries.forEach( ( entry ) => {
				if ( ! entry.isIntersecting ) return;
				entry.target.classList.add( 'is-in-view' );
				observer.unobserve( entry.target );
			} );
		},
		{ threshold: 0.2, rootMargin: '0px 0px -10% 0px' }
	);
	items.forEach( ( { section } ) => observer.observe( section ) );

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', onScroll );
	onScroll();
}
