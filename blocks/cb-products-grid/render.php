<?php
/**
 * Block template for CB Products Grid.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow          = $attributes['eyebrow'] ?? '';
$headline         = $attributes['headline'] ?? '';
$lede             = $attributes['lede'] ?? '';
$selected_ids     = array_values(
	array_filter(
		array_map(
			function ( $row ) {
				return absint( $row['productId'] ?? 0 );
			},
			$attributes['productIds'] ?? array()
		)
	)
);
$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$query_args = array(
	'post_type'      => 'product',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
	'no_found_rows'  => true,
);

// Hand-picked products show in the order chosen; none picked = all products.
if ( $selected_ids ) {
	$query_args['post__in'] = $selected_ids;
	$query_args['orderby']  = 'post__in';
}

$products = new WP_Query( $query_args );

if ( ! $products->have_posts() ) {
	return;
}

$block_id           = $attributes['anchor'] ?? wp_unique_id( 'cb-products-grid-' );
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'products',
		'id'    => $block_id,
	)
);

?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="products-header row align-items-end g-4 g-lg-5">
			<div class="col-12 <?php echo $lede ? 'col-lg-7' : 'col-lg-8'; ?>">
				<?php if ( $eyebrow ) { ?>
					<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
				<?php } ?>
				<?php if ( $headline ) { ?>
					<h2 class="products-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php } ?>
			</div>
			<?php if ( $lede ) { ?>
			<div class="col-12 col-lg-5">
				<div class="products-intro prose-md"><?php echo wp_kses_post( $lede ); ?></div>
			</div>
			<?php } ?>
		</div>

		<?php
		if ( $products->have_posts() ) {
			?>
			<div class="product-grid">
				<?php
				while ( $products->have_posts() ) {
					$products->the_post();
					$the_post_id = get_the_ID();
					$card_tag    = get_post_meta( $the_post_id, 'tag', true );
					$card_intro  = get_post_meta( $the_post_id, 'card_intro', true );
					$has_url     = (bool) trim( get_the_content() );
					$el          = $has_url ? 'a' : 'div';
					$classes     = 'product-card';
					if ( $has_url ) {
						$classes .= ' product-card--linked';
					}
					?>
					<<?= esc_attr( $el ); ?> class="<?= esc_attr( $classes ); ?>"
					<?php
					if ( $has_url ) {
						?>
						href="<?= esc_url( get_permalink() ); ?>"
						<?php
					}
					?>
					>
						<div class="product-card-img-wrap">
							<?php
							if ( $card_tag ) {
								?>
							<span class="product-card-tag"><?= esc_html( $card_tag ); ?></span>
								<?php
							}
							?>
							<?php
							if ( has_post_thumbnail() ) {
								echo get_the_post_thumbnail(
									$the_post_id,
									'large',
									array(
										'class' => 'product-card-img',
										'alt'   => esc_attr( get_the_title() ),
									)
								);
							}
							?>
						</div>
						<div class="product-card-body">
							<div class="product-card-title"><?= esc_html( get_the_title() ); ?></div>
							<?php
							if ( $card_intro ) {
								?>
							<div class="product-card-desc"><?= esc_html( $card_intro ); ?></div>
								<?php
							}
							if ( $has_url ) {
								?>
							<span class="product-card-link">
								View Product
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
									<path d="M5 12h14M12 5l7 7-7 7"/>
								</svg>
							</span>
								<?php
							}
							?>
						</div>
					</<?= esc_attr( $el ); ?>>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>
			<?php
		}
		?>
	</div>	
</section>
