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

/**
 * Resolve a project's display category name for card meta labels.
 *
 * Prefers the dedicated `project_cat` taxonomy, falling back to
 * `application_cat`. Ported from cb-hts2026's inc/cb-utility.php.
 *
 * @param int $post_id Project post ID.
 * @return string Term name, or an empty string when the project is uncategorised.
 */
function cb_hts_js_2026_project_category_name( $post_id ) {
	foreach ( array( 'project_cat', 'application_cat' ) as $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$term = reset( $terms );
			return $term->name;
		}
	}

	return '';
}

/**
 * [contact_phone] shortcode — tel: link for the Site-Wide Settings phone
 * number. Ported from cb-hts2026's inc/cb-utility.php, reading from this
 * theme's own settings page instead of ACF, and with an inline SVG icon
 * (no icon font in this theme) instead of Font Awesome.
 *
 * Attributes: class, text (custom anchor text, defaults to the number),
 * icon (truthy prepends the phone icon).
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function cb_hts_js_2026_contact_phone_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'class' => '',
			'text'  => '',
			'icon'  => false,
		),
		$atts,
		'contact_phone'
	);

	$phone = cb_hts_js_2026_get_setting( 'phone' );

	if ( ! $phone ) {
		return '';
	}

	$icon_html = $atts['icon'] ? '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg> ' : '';

	$anchor_text = $icon_html . ( ! empty( $atts['text'] ) ? wp_kses_post( $atts['text'] ) : esc_html( $phone ) );

	return '<a href="tel:' . esc_attr( parse_phone( $phone ) ) . '" class="' . esc_attr( $atts['class'] ) . '">' . $anchor_text . '</a>';
}
add_shortcode( 'contact_phone', 'cb_hts_js_2026_contact_phone_shortcode' );
