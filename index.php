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
?>

<section class="insights-hero">
	<div class="container">
		<h1 class="insights-hero__title"><?php esc_html_e( 'Insights', 'cb-hts-js-2026' ); ?></h1>
		<p class="insights-hero__lede"><?php esc_html_e( 'News, guides and insight from HTS Industries.', 'cb-hts-js-2026' ); ?></p>
	</div>
</section>

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
