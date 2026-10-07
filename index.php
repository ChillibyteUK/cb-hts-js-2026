<?php
/**
 * Blog index (Insights) template — also serves as the generic fallback.
 * Hero is hardcoded here: the page set as Settings → Reading → "Posts
 * page" carries no hero content of its own. That page's own content (if
 * any is ever added) is output separately first, before the post cards.
 *
 * @package cb-hts-js-2026
 */

get_header();

$latest_posts = get_posts(
	array(
		'post_type'   => 'post',
		'numberposts' => 1,
	)
);
$latest       = $latest_posts ? $latest_posts[0] : null;
$has_visual   = $latest && has_post_thumbnail( $latest->ID );
$guide_count  = (int) wp_count_posts( 'post' )->publish;
?>

<section class="hero hero-index" id="insights-hero">
	<div class="container">
		<div class="hero-split<?php echo $has_visual ? '' : ' hero-split--single'; ?>">
			<div class="hero-content">
				<h1 class="hero-h1"><?php esc_html_e( 'Insights', 'cb-hts-js-2026' ); ?></h1>
				<div class="hero-lede"><?php esc_html_e( 'News, guides and insight from HTS Industries.', 'cb-hts-js-2026' ); ?></div>
				<?php
				if ( $latest ) {
					?>
					<div class="hero-actions">
						<a href="<?php echo esc_url( get_permalink( $latest->ID ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Read the latest guide', 'cb-hts-js-2026' ); ?></a>
					</div>
					<?php
				}
				?>
			</div>
			<?php
			if ( $has_visual ) {
				?>
				<div class="hero-visual">
					<div class="hero-img-wrap">
						<?php echo get_the_post_thumbnail( $latest->ID, 'large', array( 'class' => 'hero-img' ) ); ?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
<?php
if ( $has_visual ) {
	?>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			return;
		}

		var section = document.getElementById('insights-hero');
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
	<?php
}
?>

<?php
$posts_page = get_post( (int) get_option( 'page_for_posts' ) );
if ( $posts_page && trim( $posts_page->post_content ) ) {
	?>
	<div class="container insights-intro">
		<?php echo apply_filters( 'the_content', $posts_page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content filter output. ?>
	</div>
	<?php
}

if ( have_posts() ) {
	?>
	<div class="container insights-index">
		<div class="related-posts__grid">
			<?php
			while ( have_posts() ) {
				the_post();
				cb_hts_js_2026_render_post_card();
			}
			?>
		</div>

		<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
	</div>
	<?php
} else {
	?>
	<div class="container insights-index">
		<p><?php esc_html_e( 'Nothing found.', 'cb-hts-js-2026' ); ?></p>
	</div>
	<?php
}

get_footer();
