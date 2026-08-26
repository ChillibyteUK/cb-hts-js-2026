<?php
/**
 * Block template for CB Product Used.
 *
 * A single linked card promoting the product featured in a case study. Copy
 * is pulled from the linked product post, with optional per-placement
 * overrides.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$product_id = absint( $attributes['productId'] ?? 0 );
$product    = $product_id ? get_post( $product_id ) : null;

if ( ! $product instanceof WP_Post || 'publish' !== $product->post_status ) {
	return;
}

$eyebrow = $attributes['eyebrow'] ?? '';
if ( ! $eyebrow ) {
	$eyebrow = __( 'Product used in this case study', 'cb-hts-js-2026' );
}

$heading = $attributes['heading'] ?? '';
if ( ! $heading ) {
	$heading = get_the_title( $product->ID );
}

$summary = $attributes['summary'] ?? '';
if ( ! $summary ) {
	$summary = get_the_excerpt( $product->ID );
}

$image_id = ! empty( $attributes['imageId'] ) ? absint( $attributes['imageId'] ) : get_post_thumbnail_id( $product->ID );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'product-used' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<a class="product-used-card" href="<?php echo esc_url( get_permalink( $product->ID ) ); ?>">
			<div class="product-used-media">
				<?php
				if ( $image_id ) {
					echo wp_get_attachment_image(
						$image_id,
						'medium_large',
						false,
						array(
							'class'   => 'product-used-img',
							'loading' => 'lazy',
							'alt'     => esc_attr( $heading ),
						)
					);
				}
				?>
			</div>
			<div class="product-used-body">
				<div class="product-used-eyebrow tag"><?php echo esc_html( $eyebrow ); ?></div>
				<h2 class="product-used-title h3"><?php echo esc_html( $heading ); ?></h2>
				<?php if ( $summary ) { ?>
					<p class="product-used-summary"><?php echo esc_html( $summary ); ?></p>
				<?php } ?>
				<span class="product-used-link">
					<?php echo esc_html( 'Explore ' . get_the_title( $product->ID ) ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
						<path d="M5 12h14M12 5l7 7-7 7"/>
					</svg>
				</span>
			</div>
		</a>
	</div>
</section>
