<?php
/**
 * Block template for CB Feature Grid.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = $attributes['eyebrow'] ?? '';
$headline = $attributes['headline'] ?? '';
$items    = $attributes['items'] ?? array();

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$items = array_filter(
	$items,
	function ( $item ) {
		return ! empty( $item['title'] ) || ! empty( $item['content'] );
	}
);

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'feature-grid' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<?php
		if ( $eyebrow ) {
			?>
		<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
			<?php
		}
		if ( $headline ) {
			?>
		<h2 class="feature-grid-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
			<?php
		}
		if ( $items ) {
			?>
		<div class="feature-grid-cells">
			<?php
			foreach ( $items as $item ) {
				?>
			<div class="feature-grid-cell">
				<?php
				if ( ! empty( $item['title'] ) ) {
					?>
				<h3 class="feature-grid-title"><?php echo esc_html( $item['title'] ); ?></h3>
					<?php
				}
				if ( ! empty( $item['content'] ) ) {
					?>
				<div class="feature-grid-content"><?php echo wp_kses_post( $item['content'] ); ?></div>
					<?php
				}
				?>
			</div>
				<?php
			}
			?>
		</div>
			<?php
		}
		?>
	</div>
</section>
