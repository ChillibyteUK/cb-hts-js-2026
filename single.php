<?php
/**
 * Single post template — paper split hero reusing the CB Home Hero
 * pattern (meta strip, H1, first-paragraph lede, full-height image), then
 * a 9/3 article + quick-links sidebar layout on desktop. The sidebar
 * tracks which H2 section is currently in view (src/js/toc.js) via
 * IntersectionObserver, marking the corresponding link active.
 *
 * @package cb-hts-js-2026
 */

get_header();

while ( have_posts() ) {
	the_post();

	$split   = cb_hts_js_2026_lede_and_body( get_post() );
	$lede    = $split['lede'];
	$toc     = cb_hts_js_2026_extract_toc( apply_filters( 'the_content', $split['content'] ), 'h2' );
	$minutes = cb_hts_js_2026_reading_time( $toc['content'] );

	$has_visual = has_post_thumbnail();
	?>
	<?php cb_hts_js_2026_render_breadcrumbs( cb_hts_js_2026_get_breadcrumbs(), 'cb-breadcrumbs single-breadcrumbs' ); ?>
	<section class="hero hero-post" id="post-hero">
		<div class="container">
			<div class="hero-split<?php echo $has_visual ? '' : ' hero-split--single'; ?>">
				<div class="hero-content">
					<h1 class="hero-h1"><?php the_title(); ?></h1>
					<ul class="hero-meta">
						<li class="post-meta__item">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
							<?= esc_html( get_the_date() ); ?></li>
						<li class="post-meta__item">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
							HTS Industries				</li>
						<li class="post-meta__item">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
							<?= esc_html( $minutes ) . ' min read'; ?></li>
					</ul>
					<?php
					if ( $lede ) {
						?>
						<div class="hero-lede"><?php echo esc_html( $lede ); ?></div>
						<?php
					}
					?>
				</div>
				<?php
				if ( $has_visual ) {
					?>
					<div class="hero-visual">
						<div class="hero-img-wrap">
							<?php the_post_thumbnail( 'large', array( 'class' => 'hero-img' ) ); ?>
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

			var section = document.getElementById('post-hero');
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
	<div class="container single-body">
		<div class="row">
			<div class="col-12 col-lg-9">
				<article <?php post_class( 'single-article' ); ?>>
					<?php echo $toc['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content filter output, only mutated by cb_hts_js_2026_extract_toc() to add heading ids. ?>
				</article>

				<?php
				// BlogPosting JSON-LD — author is always the organisation itself (see
				// the meta row above), never a named individual author.
				$article_schema = array(
					'@context'         => 'https://schema.org',
					'@type'            => 'BlogPosting',
					'@id'              => get_permalink() . '#article',
					'mainEntityOfPage' => get_permalink(),
					'headline'         => get_the_title(),
					'datePublished'    => get_the_date( DATE_W3C ),
					'dateModified'     => get_the_modified_date( DATE_W3C ),
					'author'           => array(
						'@type' => 'Organization',
						'name'  => 'HTS Industries',
						'url'   => home_url( '/' ),
					),
					'publisher'        => array(
						'@type' => 'Organization',
						'name'  => 'HTS Industries',
						'url'   => home_url( '/' ),
					),
				);
				if ( has_post_thumbnail() ) {
					$article_schema['image'] = get_the_post_thumbnail_url( null, 'full' );
				}
				echo '<script type="application/ld+json">' . wp_json_encode( $article_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode() output.
				?>
			</div>
			<?php
			if ( ! empty( $toc['items'] ) ) {
				?>
				<div class="col-12 col-lg-3 d-none d-lg-block">
					<nav class="toc" aria-label="<?php esc_attr_e( 'Quick links', 'cb-hts-js-2026' ); ?>">
						<p class="toc__label"><?php esc_html_e( 'Quick links', 'cb-hts-js-2026' ); ?></p>
						<ul class="toc__list">
							<?php
							foreach ( $toc['items'] as $item ) {
								?>
								<li>
									<a class="toc__link" href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
								</li>
								<?php
							}
							?>
						</ul>
					</nav>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<?php
	$prev_post = get_previous_post();
	$next_post = get_next_post();
	if ( $prev_post || $next_post ) {
		?>
		<div class="container">
			<div class="single-nav">
				<?php
				if ( $prev_post ) {
					?>
					<a class="single-nav__link single-nav__link--prev" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
						<span class="single-nav__label"><?php esc_html_e( '← Previous', 'cb-hts-js-2026' ); ?></span>
						<span class="single-nav__title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
					</a>
					<?php
				} else {
					?>
					<span></span>
					<?php
				}
				?>
				<?php
				if ( $next_post ) {
					?>
					<a class="single-nav__link single-nav__link--next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
						<span class="single-nav__label"><?php esc_html_e( 'Next →', 'cb-hts-js-2026' ); ?></span>
						<span class="single-nav__title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
					</a>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	$recent_posts = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( $recent_posts->have_posts() ) {
		?>
		<div class="container related-posts">
			<h2 class="related-posts__heading h2"><?php esc_html_e( 'More insights', 'cb-hts-js-2026' ); ?></h2>
			<div class="related-posts__grid">
				<?php
				while ( $recent_posts->have_posts() ) {
					$recent_posts->the_post();
					cb_hts_js_2026_render_post_card();
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php
	}
	?>
	<?php
}

get_footer();
