/**
 * Lenis smooth scroll. Ported from cb-hts2026's inline wp_footer script —
 * same options (smooth/lerp), same window.lenis + raf loop — as a proper
 * module here instead, since `lenis` is enqueued as a real script dependency
 * (see inc/enqueue.php) rather than relied on via load order.
 */
export function initLenis() {
	if (typeof Lenis === 'undefined') return;

	const lenis = new Lenis({
		smooth: true,
		lerp: 0.1,
	});
	window.lenis = lenis;

	function raf(time) {
		lenis.raf(time);
		requestAnimationFrame(raf);
	}
	requestAnimationFrame(raf);
}
