<?php
/**
 * Block template for CB Selected Clients.
 *
 * Renders a marquee of client logos defined in Site-Wide Settings > Clients.
 * Logos are expected to be 16:9, pre-cropped and colour-corrected. See
 * blocks/_shared/marquee-view.js for the scroll animation, shared with
 * CB Marquee Stats.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$logos = cb_hts_js_2026_get_client_logos();

if ( ! $logos ) {
	return;
}

$full_width = ! empty( $attributes['fullWidth'] );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $full_width ? 'clients-strip clients-strip--full' : 'clients-strip' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<?php if ( ! $full_width ) { ?>
	<div class="container">
	<?php } ?>
		<div class="clients-inner">
			<?php if ( ! $full_width ) { ?>
			<div class="clients-label">Selected clients</div>
			<?php } ?>
			<div class="clients-marquee" data-marquee data-marquee-speed="50" aria-label="Selected clients">
				<div class="clients-track" data-marquee-track>
					<?php
					foreach ( $logos as $logo ) {
						echo wp_get_attachment_image(
							$logo['logo_id'],
							'medium',
							false,
							array(
								'class' => 'client-logo',
								'alt'   => $logo['name'],
							)
						);
					}
					?>
				</div>
			</div>
		</div>
	<?php if ( ! $full_width ) { ?>
	</div>
	<?php } ?>
</section>
