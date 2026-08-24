<?php
/**
 * Project-specific helpers — not generically reusable across projects built
 * on this skeleton, unlike inc/utilities.php.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Restyles the Gravity Forms submit button to this theme's own .btn markup
 * instead of Gravity Forms' default, matching every other CTA on the site.
 * Ported from cb-hts2026's inc/cb-theme.php.
 *
 * @param string $button Default button markup.
 * @param array  $form   Current form.
 * @return string
 */
function cb_hts_js_2026_gform_submit_button( $button, $form ) {
	return '<button class="gform_button btn btn-primary w-100 justify-content-center" id="gform_submit_button_' . esc_attr( $form['id'] ) . '">'
		. esc_html( $form['button']['text'] )
		. '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></button>';
}
add_filter( 'gform_submit_button', 'cb_hts_js_2026_gform_submit_button', 10, 2 );
