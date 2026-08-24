<?php
/**
 * Block template for CB Product / Project Hero.
 *
 * Split hero — copy on the left, image with stat badge on the right. Reuses
 * CB Home Hero's layout classes (.hero, .hero-split, .hero-content,
 * .hero-lede, .hero-bullets, .hero-actions, .hero-visual, .hero-img-wrap,
 * .hero-badge*); .hero-tag and the bare-<span> H1 accent are this block's
 * own addition, in src/blocks/cb-product-hero.css.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$block_id             = $attributes['anchor'] ?? wp_unique_id( 'cb-product-hero-' );
$htag                 = $attributes['tag'] ?? '';
$h1                   = $attributes['h1'] ?? '';
$lede                 = $attributes['lede'] ?? '';
$bullets              = $attributes['bullets'] ?? '';
$cta_primary_text     = $attributes['ctaPrimaryText'] ?? '';
$cta_primary_url      = $attributes['ctaPrimaryUrl'] ?? '';
$cta_primary_target   = ! empty( $attributes['ctaPrimaryTarget'] );
$cta_secondary_text   = $attributes['ctaSecondaryText'] ?? '';
$cta_secondary_url    = $attributes['ctaSecondaryUrl'] ?? '';
$cta_secondary_target = ! empty( $attributes['ctaSecondaryTarget'] );
$image_id             = $attributes['imageId'] ?? 0;
$badge_number         = $attributes['badgeNumber'] ?? '';
$badge_suffix         = $attributes['badgeSuffix'] ?? '';
$badge_label          = $attributes['badgeLabel'] ?? '';

$h1_allowed      = array(
	'span' => array(),
	'br'   => array(),
);
$bullets_allowed = array(
	'strong' => array(),
	'em'     => array(),
	'br'     => array(),
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $block_id,
		'class' => 'hero',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="hero-split">
			<div class="hero-content">
				<?php
				if ( $htag ) {
					?>
				<div class="hero-tag"><?php echo esc_html( $htag ); ?></div>
					<?php
				}
				if ( $h1 ) {
					?>
				<h1 class="hero-h1"><?php echo wp_kses( $h1, $h1_allowed ); ?></h1>
					<?php
				}
				if ( $lede ) {
					?>
				<div class="hero-lede"><?php echo wp_kses_post( $lede ); ?></div>
					<?php
				}
				if ( $bullets ) {
					?>
				<ul class="hero-bullets">
					<?php
					foreach ( preg_split( '/\r\n|\n|\r/', $bullets ) as $bullet ) {
						$bullet = trim( $bullet );
						if ( '' === $bullet ) {
							continue;
						}
						?>
					<li><?php echo wp_kses( $bullet, $bullets_allowed ); ?></li>
						<?php
					}
					?>
				</ul>
					<?php
				}
				if ( $cta_primary_url || $cta_secondary_url ) {
					?>
				<div class="hero-actions">
					<?php
					if ( $cta_primary_url ) {
						?>
					<a
						href="<?php echo esc_url( $cta_primary_url ); ?>"
						class="btn btn-primary"
						<?php echo $cta_primary_target ? ' target="_blank" rel="noopener"' : ''; ?>
					>
						<?php echo esc_html( $cta_primary_text ? $cta_primary_text : 'Get in touch' ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
							<path d="M5 12h14M12 5l7 7-7 7" />
						</svg>
					</a>
						<?php
					}
					if ( $cta_secondary_url ) {
						?>
					<a
						href="<?php echo esc_url( $cta_secondary_url ); ?>"
						class="btn btn-outline-dark"
						<?php echo $cta_secondary_target ? ' target="_blank" rel="noopener"' : ''; ?>
					>
						<?php echo esc_html( $cta_secondary_text ? $cta_secondary_text : 'Learn more' ); ?>
					</a>
						<?php
					}
					?>
				</div>
					<?php
				}
				?>
			</div>
			<div class="hero-visual">
				<?php
				if ( $image_id ) {
					?>
				<div class="hero-img-wrap">
					<?php echo wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'hero-img' ) ); ?>
				</div>
					<?php
				}
				if ( $badge_number || $badge_label ) {
					?>
				<div class="hero-badge">
					<div>
						<?php
						if ( $badge_number ) {
							?>
						<div class="hero-badge-num">
							<?php
							echo esc_html( $badge_number );
							if ( $badge_suffix ) {
								?>
							<sup><?php echo esc_html( $badge_suffix ); ?></sup>
								<?php
							}
							?>
						</div>
							<?php
						}
						if ( $badge_label ) {
							?>
						<div class="hero-badge-label"><?php echo nl2br( esc_html( $badge_label ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
							<?php
						}
						?>
					</div>
				</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>

<?php if ( $image_id ) { ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var section = document.getElementById(<?php echo wp_json_encode( $block_id ); ?>);
	if (!section) return;

	var ticking = false;

	function update() {
		var rect = section.getBoundingClientRect();
		var windowHeight = window.innerHeight;

		if (rect.bottom > 0 && rect.top < windowHeight) {
			var percent = (windowHeight - rect.top) / (windowHeight + rect.height);
			percent = Math.max(0, Math.min(1, percent));
			var translateY = (percent - 0.5) * 120;
			section.style.setProperty('--hero-parallax-y', translateY.toFixed(1) + 'px');
		}

		ticking = false;
	}

	function onScroll() {
		if (!ticking) {
			window.requestAnimationFrame(update);
			ticking = true;
		}
	}

	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', onScroll);
	onScroll();
});
</script>
<?php } ?>
