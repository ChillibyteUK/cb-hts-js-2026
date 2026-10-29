<?php
/**
 * Block template for CB Client Names.
 *
 * A text-only sibling of CB Selected Clients: the same rotated title, but
 * the names come from the block's own `names` repeater instead of logos
 * from Site-Wide Settings, and they sit in a static wrapping row rather
 * than a marquee.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$label = $attributes['label'] ?? '';
$names = array_filter(
	$attributes['names'] ?? array(),
	function ( $item ) {
		return ! empty( $item['name'] );
	}
);

if ( ! $names ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'client-names' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="client-names-inner">
			<?php if ( $label ) { ?>
				<div class="client-names-label"><?php echo esc_html( $label ); ?></div>
			<?php } ?>
			<ul class="client-names-list">
				<?php foreach ( $names as $item ) { ?>
					<li><?php echo esc_html( $item['name'] ); ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</section>
