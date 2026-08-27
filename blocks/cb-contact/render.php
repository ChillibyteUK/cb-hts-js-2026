<?php
/**
 * Block template for CB Contact.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow       = $attributes['eyebrow'] ?? '';
$headline      = $attributes['headline'] ?? '';
$body          = $attributes['body'] ?? '';
$coverage      = $attributes['coverage'] ?? '';
$note_title    = $attributes['noteTitle'] ?? '';
$note_body     = $attributes['noteBody'] ?? '';
$form_title    = $attributes['formTitle'] ?? '';
$form_subtitle = $attributes['formSubtitle'] ?? '';
$form_code     = $attributes['formShortcode'] ?? '';

$phone = cb_hts_js_2026_get_setting( 'phone' );
$email = cb_hts_js_2026_get_setting( 'email' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$bg_color   = $attributes['backgroundColor'] ?? '';
$form_white = $bg_color && 'white' !== $bg_color;

// Source hardcodes id="contact" (nav-anchor target); fall back to it only
// when the editor's own HTML anchor support hasn't set one, so the two
// don't collide into a duplicate id attribute.
$anchor             = $attributes['anchor'] ?? '';
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'contact',
		'id'    => $anchor ? '' : 'contact',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="contact-inner row align-items-start">
			<div class="col-12 col-lg-5">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="contact-h2 h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				if ( $body ) {
					?>
				<div class="contact-body prose-md"><?php echo nl2br( esc_html( $body ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
					<?php
				}
				?>

				<div class="contact-details">
					<?php
					if ( $phone ) {
						?>
						<div class="contact-detail">
							<div class="contact-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.5 19.79 19.79 0 0 1 1.59 4.9 2 2 0 0 1 3.59 2.73h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 10.36a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg>
							</div>
							<div>
								<div class="contact-detail-label"><?php esc_html_e( 'Phone', 'cb-hts-js-2026' ); ?></div>
								<div class="contact-detail-value"><a href="tel:<?php echo esc_attr( parse_phone( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></div>
							</div>
						</div>
						<?php
					}

					if ( $email ) {
						?>
						<div class="contact-detail">
							<div class="contact-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
							</div>
							<div>
								<div class="contact-detail-label"><?php esc_html_e( 'Email', 'cb-hts-js-2026' ); ?></div>
								<div class="contact-detail-value"><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo antispambot( esc_html( $email ) ); ?></a></div>
							</div>
						</div>
						<?php
					}

					if ( $coverage ) {
						?>
						<div class="contact-detail">
							<div class="contact-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
							</div>
							<div>
								<div class="contact-detail-label"><?php esc_html_e( 'Coverage', 'cb-hts-js-2026' ); ?></div>
								<div class="contact-detail-value"><?php echo nl2br( esc_html( $coverage ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
							</div>
						</div>
						<?php
					}

					if ( $note_title || $note_body ) {
						?>
						<div class="contact-note">
							<?php
							if ( $note_title ) {
								?>
								<div class="contact-detail-label contact-note-title"><?php echo esc_html( $note_title ); ?></div>
								<?php
							}
							if ( $note_body ) {
								?>
								<div class="contact-note-body"><?php echo nl2br( esc_html( $note_body ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
								<?php
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>

			<div class="col-12 col-lg-7">
				<div class="contact-form<?php echo $form_white ? ' contact-form--on-bg' : ''; ?>">
					<?php
					if ( $form_title ) {
						?>
						<div class="form-title h4"><?php echo esc_html( $form_title ); ?></div>
						<?php
					}
					if ( $form_subtitle ) {
						?>
						<div class="form-subtitle"><?php echo nl2br( esc_html( $form_subtitle ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
						<?php
					}
					if ( $form_code ) {
						?>
						<div class="contact-form-shortcode"><?php echo do_shortcode( $form_code ); ?></div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
