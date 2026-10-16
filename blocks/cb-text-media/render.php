<?php
/**
 * Block template for CB Text Media.
 *
 * Two-column row: a sticky image beside an eyebrow, heading, lede, content
 * and CTA. The markup is always text first; column order comes from the
 * `order` attribute (the media column takes `order: -1` at lg+ when media
 * goes first) and stacking order on small screens from `mobileOrder` (the
 * media column takes `order: -1` below lg when media goes first), so the
 * markup stays in one place. See src/blocks/cb-text-media.css.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = $attributes['eyebrow'] ?? '';
$headline   = $attributes['headline'] ?? '';
$lede       = $attributes['lede'] ?? '';
$content    = $attributes['content'] ?? '';
$cta_text   = $attributes['ctaText'] ?? '';
$cta_url    = $attributes['ctaUrl'] ?? '';
$cta_target = ! empty( $attributes['ctaTarget'] );
$image_id   = $attributes['imageId'] ?? 0;

$media_first        = 'text-media' !== ( $attributes['order'] ?? 'media-text' );
$media_mobile_first = 'text-media' !== ( $attributes['mobileOrder'] ?? 'media-text' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$media_class  = 'text-media-media';
$media_class .= $media_first ? ' text-media-media--first' : '';
$media_class .= $media_mobile_first ? ' text-media-media--mobile-first' : '';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $image_id ? 'text-media' : 'text-media text-media--no-media' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="text-media-grid">
			<div class="text-media-text">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="text-media-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				if ( $lede ) {
					?>
				<div class="text-media-lede prose-lede"><?php echo wp_kses_post( $lede ); ?></div>
					<?php
				}
				if ( $content ) {
					?>
				<div class="text-media-content prose-md"><?php echo wp_kses_post( $content ); ?></div>
					<?php
				}
				if ( $cta_url ) {
					?>
				<a
					href="<?php echo esc_url( $cta_url ); ?>"
					<?php echo $cta_target ? ' target="_blank" rel="noopener"' : ''; ?>
					class="btn btn-primary text-media-cta"
				>
					<?php echo esc_html( $cta_text ? $cta_text : $cta_url ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
						<path d="M5 12h14M12 5l7 7-7 7" />
					</svg>
				</a>
					<?php
				}
				?>
			</div>
			<?php
			if ( $image_id ) {
				?>
			<div class="<?php echo esc_attr( $media_class ); ?>">
				<?php
				echo wp_get_attachment_image(
					$image_id,
					'large',
					false,
					array(
						'class' => 'text-media-img',
						'alt'   => esc_attr( $attributes['imageAlt'] ?? '' ),
					)
				);
				?>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
