<?php
/**
 * Block template for CB Spec Bar.
 *
 * Navy key/value strip, sits directly under a hero.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$spec_items = $attributes['specItems'] ?? array();

if ( empty( $spec_items ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'spec-bar' ) );

$anchor = ! empty( $item['anchor'] ) ? ' id="' . esc_attr( $item['anchor'] ) . '"' : '';
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="spec-bar-grid">
			<?php
			foreach ( $spec_items as $item ) {
				if ( empty( $item['label'] ) && empty( $item['value'] ) ) {
					continue;
				}
				?>
			<div class="spec-bar-item">
				<?php
				if ( ! empty( $item['label'] ) ) {
					?>
				<div class="spec-bar-label"><?= esc_html( $item['label'] ); ?></div>
					<?php
				}
				if ( ! empty( $item['value'] ) ) {
					?>
				<div class="spec-bar-value">
					<?= wp_kses( $item['value'], array( 'br' => array() ) ); ?>
					<?php
					if ( ! empty( $item['unit'] ) ) {
						?>
					<span class="spec-bar-unit"><?= esc_html( $item['unit'] ); ?></span>
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
	</div>
</section>
