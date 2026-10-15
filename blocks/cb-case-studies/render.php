<?php
/**
 * Block template for CB Case Studies.
 *
 * Up to three projects, optionally limited to one Application category
 * (application_cat term), newest first.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow      = $attributes['eyebrow'] ?? '';
$headline     = $attributes['headline'] ?? '';
$term_id      = absint( $attributes['applicationId'] ?? 0 );
$limit        = min( 3, max( 1, absint( $attributes['limit'] ?? 3 ) ) );
$button_text  = $attributes['buttonText'] ?? '';
$button_url   = $attributes['buttonUrl'] ?? '';
$button_blank = ! empty( $attributes['buttonTarget'] );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$query_args = array(
	'post_type'      => 'project',
	'posts_per_page' => $limit,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
);

if ( $term_id ) {
	$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- single bounded query.
		array(
			'taxonomy' => 'application_cat',
			'field'    => 'term_id',
			'terms'    => $term_id,
		),
	);
}

$projects = new WP_Query( $query_args );

if ( ! $projects->have_posts() ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'case-studies' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="case-studies-header">
			<div>
				<?php if ( $eyebrow ) { ?>
					<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
				<?php } ?>
				<?php if ( $headline ) { ?>
					<h2 class="case-studies-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php } ?>
			</div>
		</div>

		<div class="case-studies-grid">
			<?php
			while ( $projects->have_posts() ) {
				$projects->the_post();
				$the_post_id = get_the_ID();
				$category    = cb_hts_js_2026_project_category_name( $the_post_id );
				?>
				<a class="case-card" href="<?php echo esc_url( get_permalink() ); ?>">
					<div class="case-card-media">
						<?php if ( $category ) { ?>
							<span class="case-card-tag"><?php echo esc_html( $category ); ?></span>
						<?php } ?>
						<?php
						if ( has_post_thumbnail() ) {
							echo get_the_post_thumbnail(
								$the_post_id,
								'large',
								array(
									'class'   => 'case-card-img',
									'loading' => $projects->current_post < 2 ? 'eager' : 'lazy',
									'alt'     => esc_attr( get_the_title() ),
								)
							);
						}
						?>
					</div>
					<div class="case-card-body">
						<h3 class="case-card-title"><?php echo esc_html( get_the_title() ); ?></h3>
						<span class="case-card-link">
							<?php esc_html_e( 'Read Case Study', 'cb-hts-js-2026' ); ?>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
								<path d="M5 12h14M12 5l7 7-7 7" />
							</svg>
						</span>
					</div>
				</a>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>

		<?php if ( $button_url ) { ?>
			<div class="case-studies-cta">
				<a
					href="<?php echo esc_url( $button_url ); ?>"
					<?php echo $button_blank ? ' target="_blank" rel="noopener"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static string. ?>
					class="btn btn-outline-dark"
				>
					<?php echo esc_html( $button_text ? $button_text : __( 'Explore all projects', 'cb-hts-js-2026' ) ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
						<path d="M5 12h14M12 5l7 7-7 7" />
					</svg>
				</a>
			</div>
		<?php } ?>
	</div>
</section>
