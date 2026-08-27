<?php
/**
 * Enqueue theme CSS/JS. filemtime versioning, no dependencies (no jQuery,
 * no Bootstrap JS) — plain vanilla output, loads immediately.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue theme.min.css.
 *
 * @return void
 */
function cb_hts_js_2026_enqueue_styles() {
	$rel = '/css/theme.min.css';
	$abs = get_stylesheet_directory() . $rel;
	if ( file_exists( $abs ) ) {
		wp_enqueue_style( 'lc-skeleton-theme', get_stylesheet_directory_uri() . $rel, array(), filemtime( $abs ) );
	}
}
add_action( 'wp_enqueue_scripts', 'cb_hts_js_2026_enqueue_styles' );

/**
 * Enqueue theme.min.js.
 *
 * @return void
 */
function cb_hts_js_2026_enqueue_scripts() {

	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/gsap.min.js', array(), '3.12.7', true );
	// wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/ScrollTrigger.min.js', array( 'gsap' ), '3.12.7', true );

	wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.2.10' );
	wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.2.10', true );

	wp_enqueue_style( 'glightbox', 'https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.1/css/glightbox.min.css', array(), '3.3.1' );
	wp_enqueue_script( 'glightbox', 'https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.1/js/glightbox.min.js', array(), '3.3.1', true );

	wp_enqueue_style( 'lenis-style', 'https://unpkg.com/lenis@1.3.11/dist/lenis.css', array() );
	wp_enqueue_script( 'lenis', 'https://unpkg.com/lenis@1.3.11/dist/lenis.min.js', array(), '1.3.11', true );

	$rel = '/js/theme.min.js';
	$abs = get_stylesheet_directory() . $rel;
	if ( file_exists( $abs ) ) {
		wp_enqueue_script( 'lc-skeleton-theme', get_stylesheet_directory_uri() . $rel, array( 'lenis' ), filemtime( $abs ), true );
	}
}
add_action( 'wp_enqueue_scripts', 'cb_hts_js_2026_enqueue_scripts' );
