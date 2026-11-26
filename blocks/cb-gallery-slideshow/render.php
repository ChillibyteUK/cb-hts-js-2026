<?php
/**
 * Block template for CB Gallery Slideshow.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$images = array_values( array_filter( $attributes['images'] ?? array() ) );

// Minimum of three images — fewer than that and the block renders nothing.
if ( count( $images ) < 3 ) {
	return;
}

$eyebrow = $attributes['eyebrow'] ?? '';
$heading = $attributes['heading'] ?? '';
$lede    = $attributes['lede'] ?? '';

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$uid = wp_unique_id( 'gallery-slideshow-' );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'gallery-slideshow' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<?php
		if ( $eyebrow || $heading || $lede ) {
			?>
		<div class="gallery-slideshow-header">
			<?php
			if ( $eyebrow ) {
				?>
			<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
				<?php
			}
			if ( $heading ) {
				?>
			<h2 class="intro-headline h2"><?php echo wp_kses( $heading, $headline_allowed ); ?></h2>
				<?php
			}
			if ( $lede ) {
				?>
			<div class="gallery-slideshow-lede"><?php echo wp_kses_post( wpautop( $lede ) ); ?></div>
				<?php
			}
			?>
		</div>
			<?php
		}
		?>
		<div class="gallery-slideshow-carousel" data-gallery-slideshow>
			<div class="gallery-slideshow-track" tabindex="0" role="region" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Image gallery', 'cb-hts-js-2026' ); ?>">
				<?php
				foreach ( $images as $i => $image_id ) {
					$caption = wp_get_attachment_caption( $image_id );
					?>
				<a
					href="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>"
					class="gallery-slideshow-item"
					data-glightbox="<?php echo $caption ? esc_attr( 'title: ' . $caption ) : 'type: image'; ?>"
					data-gallery="<?php echo esc_attr( $uid ); ?>"
				>
					<?php echo wp_get_attachment_image( $image_id, 'large', false, array( 'loading' => $i < 3 ? 'eager' : 'lazy' ) ); ?>
				</a>
					<?php
				}
				?>
			</div>
			<button type="button" class="gallery-slideshow-nav gallery-slideshow-nav-prev" aria-label="<?php esc_attr_e( 'Previous images', 'cb-hts-js-2026' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><polyline points="15 18 9 12 15 6" /></svg>
			</button>
			<button type="button" class="gallery-slideshow-nav gallery-slideshow-nav-next" aria-label="<?php esc_attr_e( 'Next images', 'cb-hts-js-2026' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><polyline points="9 18 15 12 9 6" /></svg>
			</button>
		</div>
	</div>
</section>

<?php
add_action(
	'wp_footer',
	function () {
		static $printed = false;
		if ( $printed ) {
			return;
		}
		$printed = true;
		?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('[data-gallery-slideshow]').forEach(function (root) {
		var track = root.querySelector('.gallery-slideshow-track');
		var prev = root.querySelector('.gallery-slideshow-nav-prev');
		var next = root.querySelector('.gallery-slideshow-nav-next');
		var step = function () {
			var item = track.querySelector('.gallery-slideshow-item');
			return item ? item.getBoundingClientRect().width + parseFloat(getComputedStyle(track).columnGap || 0) : track.clientWidth;
		};
		var update = function () {
			prev.disabled = track.scrollLeft <= 1;
			next.disabled = track.scrollLeft >= track.scrollWidth - track.clientWidth - 1;
		};
		prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
		next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });
		track.addEventListener('scroll', update, { passive: true });
		window.addEventListener('resize', update);
		update();
	});
	if (typeof GLightbox !== 'undefined') {
		var groups = {};
		document.querySelectorAll('.gallery-slideshow-item').forEach(function (a) {
			groups[a.dataset.gallery] = true;
		});
		Object.keys(groups).forEach(function (g) {
			GLightbox({ selector: '.gallery-slideshow-item[data-gallery="' + g + '"]', loop: true });
		});
	}
});
</script>
		<?php
	},
	999
);
