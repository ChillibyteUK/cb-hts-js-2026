<?php
/**
 * Block template for CB Image CTA.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$block_id                = $attributes['anchor'] ?? wp_unique_id( 'cb-image-cta-' );
$image_id                = $attributes['imageId'] ?? 0;
$eyebrow                 = $attributes['eyebrow'] ?? '';
$headline                = $attributes['headline'] ?? '';
$content                 = $attributes['content'] ?? '';
$button_text             = $attributes['buttonText'] ?? '';
$button_url              = $attributes['buttonUrl'] ?? '';
$button_target           = ! empty( $attributes['buttonTarget'] );
$button_secondary_text   = $attributes['buttonSecondaryText'] ?? '';
$button_secondary_url    = $attributes['buttonSecondaryUrl'] ?? '';
$button_secondary_target = ! empty( $attributes['buttonSecondaryTarget'] );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $block_id,
		'class' => 'image-cta',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<?php
	if ( $image_id ) {
		?>
	<div class="image-cta-media" aria-hidden="true">
		<?php
		echo wp_get_attachment_image(
			$image_id,
			'full',
			false,
			array(
				'class' => 'image-cta-media-img',
				'alt'   => '',
			)
		);
		?>
	</div>
		<?php
	}
	?>

	<div class="image-cta-overlay" aria-hidden="true"></div>

	<div class="container">
		<div class="image-cta-inner">
			<?php
			if ( $eyebrow ) {
				?>
			<div class="eyebrow eyebrow--plain"><?php echo esc_html( $eyebrow ); ?></div>
				<?php
			}
			if ( $headline ) {
				?>
			<h2 class="image-cta-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php
			}
			if ( $content ) {
				?>
			<div class="image-cta-content prose-md"><?php echo wp_kses_post( $content ); ?></div>
				<?php
			}
			if ( $button_url || $button_secondary_url ) {
				?>
			<div class="image-cta-actions">
				<?php
				if ( $button_url ) {
					?>
				<a
					href="<?php echo esc_url( $button_url ); ?>"
					class="btn btn-primary w-100 w-md-auto"
					<?php echo $button_target ? ' target="_blank" rel="noopener"' : ''; ?>
				>
					<?php echo esc_html( $button_text ? $button_text : 'Get Started' ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
						<path d="M5 12h14M12 5l7 7-7 7" />
					</svg>
				</a>
					<?php
				}
				if ( $button_secondary_url ) {
					?>
				<a
					href="<?php echo esc_url( $button_secondary_url ); ?>"
					class="btn btn-outline-light w-100 w-md-auto"
					<?php echo $button_secondary_target ? ' target="_blank" rel="noopener"' : ''; ?>
				>
					<?php echo esc_html( $button_secondary_text ? $button_secondary_text : 'Learn More' ); ?>
				</a>
					<?php
				}
				?>
			</div>
				<?php
			}
			?>
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
			var translateY = (percent - 0.5) * 240;
			section.style.setProperty('--image-cta-parallax-y', translateY.toFixed(1) + 'px');
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
