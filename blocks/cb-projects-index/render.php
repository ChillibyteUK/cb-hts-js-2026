<?php
/**
 * Block template for CB Projects Index.
 *
 * Full listing of case studies as image cards, with an optional filter bar
 * built from `project_cat`. Filtering is client side — every card is in the
 * markup and the buttons just hide the ones that don't match, so the block
 * works with JS off (all projects visible, no filter bar interaction).
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow      = $attributes['eyebrow'] ?? '';
$headline     = $attributes['headline'] ?? '';
$intro        = $attributes['intro'] ?? '';
$show_filters = ! empty( $attributes['showFilters'] );
$max_projects = (int) ( $attributes['postsPerPage'] ?? 0 );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$projects = new WP_Query(
	array(
		'post_type'      => 'project',
		'posts_per_page' => $max_projects > 0 ? $max_projects : -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);

if ( ! $projects->have_posts() ) {
	return;
}

$terms = array();

if ( $show_filters ) {
	$found = get_terms(
		array(
			'taxonomy'   => 'project_cat',
			'hide_empty' => true,
		)
	);

	if ( ! empty( $found ) && ! is_wp_error( $found ) ) {
		$terms = $found;
	}
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'projects-index' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="projects-index-header row align-items-end">
			<div class="col-12 col-lg-7">
				<?php if ( $eyebrow ) { ?>
					<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
				<?php } ?>
				<?php if ( $headline ) { ?>
					<h2 class="projects-index-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php } ?>
			</div>
			<div class="col-12 col-lg-4 offset-lg-1">
				<?php if ( $intro ) { ?>
					<div class="projects-index-intro"><?php echo wp_kses_post( $intro ); ?></div>
				<?php } ?>
			</div>
		</div>

		<?php if ( ! empty( $terms ) ) { ?>
			<div class="projects-index-filters" role="group" aria-label="<?php esc_attr_e( 'Filter case studies by category', 'cb-hts-js-2026' ); ?>">
				<button type="button" class="projects-index-filter is-active" data-filter="all" aria-pressed="true">
					<?php esc_html_e( 'All projects', 'cb-hts-js-2026' ); ?>
				</button>
				<?php foreach ( $terms as $filter_term ) { ?>
					<button type="button" class="projects-index-filter" data-filter="<?php echo esc_attr( $filter_term->slug ); ?>" aria-pressed="false">
						<?php echo esc_html( $filter_term->name ); ?>
					</button>
				<?php } ?>
			</div>
		<?php } ?>

		<div class="projects-index-grid">
			<?php
			while ( $projects->have_posts() ) {
				$projects->the_post();
				$the_post_id = get_the_ID();
				$card_terms  = get_the_terms( $the_post_id, 'project_cat' );
				$slugs       = array();

				if ( ! empty( $card_terms ) && ! is_wp_error( $card_terms ) ) {
					$slugs = wp_list_pluck( $card_terms, 'slug' );
				}

				$category = cb_hts_js_2026_project_category_name( $the_post_id );
				?>
				<a class="projects-index-card" href="<?php echo esc_url( get_permalink() ); ?>" data-cats="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>">
					<div class="projects-index-card-media">
						<?php
						if ( has_post_thumbnail() ) {
							echo get_the_post_thumbnail(
								$the_post_id,
								'medium_large',
								array(
									'class'   => 'projects-index-card-img',
									'loading' => $projects->current_post < 3 ? 'eager' : 'lazy',
									'alt'     => esc_attr( get_the_title() ),
								)
							);
						}
						?>
						<div class="projects-index-card-overlay"></div>
					</div>
					<div class="projects-index-card-body">
						<?php if ( $category ) { ?>
							<div class="projects-index-card-meta"><?php echo esc_html( $category ); ?></div>
						<?php } ?>
						<h3 class="projects-index-card-title"><?php echo esc_html( get_the_title() ); ?></h3>
					</div>
				</a>
			<?php } ?>
			<?php wp_reset_postdata(); ?>
		</div>

		<p class="projects-index-empty" hidden><?php esc_html_e( 'No case studies match that filter.', 'cb-hts-js-2026' ); ?></p>
	</div>
</section>
