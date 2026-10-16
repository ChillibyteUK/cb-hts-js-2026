<?php
/**
 * Block template for CB Latest News.
 *
 * The three most recent Insights posts as cards, using the shared
 * cb_hts_js_2026_render_post_card() (the same card as the Insights index and
 * the single-post related section). Desktop (992px+) is a 3-up grid; below
 * that the cards become a Swiper slider (one card on phones, two on tablets)
 * that bleeds to both screen edges — see src/blocks/cb-latest-news.css. Swiper
 * is initialised only below 992px and destroyed again on resize, so desktop
 * never carries Swiper's inline styles. Swiper itself is enqueued site-wide in
 * inc/enqueue.php.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$headline     = $attributes['headline'] ?? '';
$button_text  = $attributes['buttonText'] ?? '';
$button_url   = $attributes['buttonUrl'] ?? '';
$button_blank = ! empty( $attributes['buttonTarget'] );

if ( ! $button_url ) {
	$posts_page_id = (int) get_option( 'page_for_posts' );
	$button_url    = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/insights/' );
}

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$news = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $news->have_posts() ) {
	return;
}

$slider_id = wp_unique_id( 'latest-news-slider-' );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'latest-news' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="latest-news-header">
			<?php
			if ( $headline ) {
				?>
			<h2 class="latest-news-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php
			}
			?>
			<a
				href="<?php echo esc_url( $button_url ); ?>"
				<?php echo $button_blank ? ' target="_blank" rel="noopener"' : ''; ?>
				class="btn btn-outline-dark latest-news-cta"
			>
				<?php echo esc_html( $button_text ? $button_text : __( 'See all', 'cb-hts-js-2026' ) ); ?>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
					<path d="M5 12h14M12 5l7 7-7 7" />
				</svg>
			</a>
		</div>

		<div class="swiper latest-news-swiper" id="<?php echo esc_attr( $slider_id ); ?>">
			<div class="swiper-wrapper">
				<?php
				while ( $news->have_posts() ) {
					$news->the_post();
					?>
				<div class="swiper-slide">
					<?php cb_hts_js_2026_render_post_card(); ?>
				</div>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</div>
</section>
<?php
wp_add_inline_script(
	'swiper',
	'(function () {
		var el = document.getElementById("' . esc_js( $slider_id ) . '");
		if (!el || !window.Swiper) { return; }
		var mq = window.matchMedia("(max-width: 991.98px)");
		var slider = null;
		function sync() {
			if (mq.matches) {
				if (!slider) {
					var pad = parseFloat(window.getComputedStyle(el.closest(".container")).paddingLeft) || 0;
					slider = new Swiper(el, {
						slidesPerView: "auto",
						spaceBetween: 16,
						slidesOffsetBefore: pad,
						slidesOffsetAfter: pad,
						speed: 500
					});
				}
			} else if (slider) {
				slider.destroy(true, true);
				slider = null;
			}
		}
		mq.addEventListener("change", sync);
		sync();
	})();'
);
