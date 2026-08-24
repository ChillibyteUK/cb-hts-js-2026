<?php
/**
 * Block template for CB Marquee Stats.
 *
 * Renders each stat/title pair from the `items` attribute inside the
 * scrolling track — see blocks/cb-marquee-stats/view.js for the scroll
 * animation.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$items = $attributes['items'] ?? array();
$items = array_filter(
	$items,
	function ( $item ) {
		return ! empty( $item['stat'] ) || ! empty( $item['title'] );
	}
);

if ( ! $items ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'        => 'marquee',
		'data-marquee' => '',
		'aria-hidden'  => 'true',
	)
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="marquee-track" data-marquee-track>
		<?php
		foreach ( $items as $item ) {
			$stat   = $item['stat'] ?? '';
			$btitle = $item['title'] ?? '';
			?>
		<span class="marquee-item">
			<?php
			if ( $stat ) {
				?>
			<span class="marquee-num"><?php echo esc_html( $stat ); ?></span>
				<?php
			}
			if ( $btitle ) {
				?>
			<span class="marquee-title"><?php echo esc_html( $btitle ); ?></span>
				<?php
			}
			?>
		</span>
			<?php
		}
		?>
	</div>
</div>
