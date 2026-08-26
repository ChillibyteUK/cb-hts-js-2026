<?php
/**
 * Block template for CB Case Study Sidebar.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$facts              = $attributes['facts'] ?? array();
$cta_text           = $attributes['ctaText'] ?? '';
$cta_url            = $attributes['ctaUrl'] ?? '';
$cta_target         = ! empty( $attributes['ctaTarget'] );
$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'cs-sidebar' ) );
?>
<aside <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="cs-sidebar-inner">
		<?php
		foreach ( $facts as $item ) {
			$label       = $item['label'] ?? '';
			$value       = $item['value'] ?? '';
			$link_url    = $item['link'] ?? '';
			$link_text   = $item['linkText'] ?? '';
			$link_target = ! empty( $item['linkTarget'] );

			if ( empty( $label ) && empty( $value ) && empty( $link_url ) ) {
				continue;
			}
			?>
			<div class="cs-sidebar-block">
				<?php
				if ( $label ) {
					?>
				<div class="cs-sidebar-label"><?php echo esc_html( $label ); ?></div>
					<?php
				}
				?>
				<div class="cs-sidebar-value">
					<?php
					if ( $link_url ) {
						?>
						<a href="<?php echo esc_url( $link_url ); ?>"
						<?php
						if ( $link_target ) {
							?>
							target="_blank" rel="noopener"
							<?php
						}
						?>
						><?php echo esc_html( $link_text ? $link_text : $value ); ?></a>
						<?php
					} elseif ( $value ) {
						echo nl2br( esc_html( $value ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string.
					}
					?>
				</div>
			</div>
			<?php
		}

		if ( $cta_url ) {
			?>
		<a href="<?php echo esc_url( $cta_url ); ?>"
			<?php
			if ( $cta_target ) {
				?>
			target="_blank" rel="noopener"
				<?php
			}
			?>
			class="btn btn-primary cs-sidebar-cta"><?php echo esc_html( $cta_text ? $cta_text : 'Discuss a similar project' ); ?></a>
			<?php
		}
		?>
	</div>
</aside>
