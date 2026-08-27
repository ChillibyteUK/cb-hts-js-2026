<?php
/**
 * Block template for CB Projects Grid.
 *
 * Fixed 5-card mosaic of the 5 most recent projects (publish date, newest
 * first — deliberately not the menu_order manual ordering the Applications/
 * Products grids and Projects Index use, so this "featured work" section on
 * the homepage always reflects latest work automatically) — the first card
 * is a hero tile (span 4x2), the fourth is wide (span 4x1), the rest are
 * standard (span 2x1). A card is only a link when its post has body content,
 * otherwise it renders as a non-interactive tile — same pattern as CB
 * Applications Grid.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = $attributes['eyebrow'] ?? '';
$headline = $attributes['headline'] ?? '';

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$projects = new WP_Query(
	array(
		'post_type'      => 'project',
		'posts_per_page' => 5,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	)
);

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'projects' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="projects-header row align-items-end">
			<div class="col-12 col-lg-8">
				<?php if ( $eyebrow ) { ?>
					<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
				<?php } ?>
				<?php if ( $headline ) { ?>
					<h2 class="projects-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php } ?>
			</div>
		</div>

		<?php if ( $projects->have_posts() ) { ?>
			<div class="projects-mosaic">
				<?php
				while ( $projects->have_posts() ) {
					$projects->the_post();
					$the_post_id = get_the_ID();
					$has_url     = (bool) trim( get_the_content() );
					$el          = $has_url ? 'a' : 'div';
					$classes     = 'proj-card';

					if ( $has_url ) {
						$classes .= ' proj-card--linked';
					}

					if ( 0 === $projects->current_post ) {
						$classes .= ' proj-card--hero';
					} elseif ( 3 === $projects->current_post ) {
						$classes .= ' proj-card--wide';
					} else {
						$classes .= ' proj-card--std';
					}

					$terms = get_the_terms( $the_post_id, 'application_cat' );
					$aterm = ! empty( $terms ) && ! is_wp_error( $terms ) ? reset( $terms ) : null;
					?>
					<<?php echo tag_escape( $el ); ?> class="<?php echo esc_attr( $classes ); ?>"<?php if ( $has_url ) { ?> href="<?php echo esc_url( get_permalink() ); ?>"<?php } ?>>
						<?php
						if ( has_post_thumbnail() ) {
							echo get_the_post_thumbnail(
								$the_post_id,
								'large',
								array(
									'class' => 'proj-card-img',
									'alt'   => esc_attr( get_the_title() ),
								)
							);
						}
						?>
						<div class="proj-card-overlay"></div>
						<div class="proj-card-body">
							<?php if ( $aterm ) { ?>
								<div class="proj-card-meta"><?php echo esc_html( $aterm->name ); ?></div>
							<?php } ?>
							<div class="proj-card-title"><?php echo esc_html( get_the_title() ); ?></div>
						</div>
					</<?php echo tag_escape( $el ); ?>>
				<?php } ?>
				<?php wp_reset_postdata(); ?>
			</div>
		<?php } ?>
	</div>
</section>
