<?php
/**
 * Block template for CB Heading.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$heading = $attributes['heading'] ?? '';

if ( ! $heading ) {
	return;
}

$eyebrow    = $attributes['eyebrow'] ?? '';
$as_section = ! empty( $attributes['asSection'] );

$level = ! empty( $attributes['level'] ) ? strtolower( $attributes['level'] ) : 'h2';
if ( ! in_array( $level, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
	$level = 'h2';
}

$size = ! empty( $attributes['size'] ) ? strtolower( $attributes['size'] ) : 'h3';
if ( ! in_array( $size, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
	$size = 'h3';
}

$heading_allowed = array(
	'span' => array(),
	'br'   => array(),
);

// Plain heading: the wrapper attributes sit on the heading tag itself.
if ( ! $eyebrow && ! $as_section ) {
	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'cb-heading ' . $size ) );

	printf(
		'<%1$s %2$s>%3$s</%1$s>',
		tag_escape( $level ),
		$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes.
		wp_kses( $heading, $heading_allowed )
	);
	return;
}

// Eyebrow and/or stand-alone section: a wrapper div carries the block attributes.
$group_class        = $as_section ? 'cb-heading-group cb-heading-group--section' : 'cb-heading-group';
$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $group_class ) );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<?php
	if ( $as_section ) {
		?>
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-8">
		<?php
	}
	if ( $eyebrow ) {
		?>
		<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
		<?php
	}
	printf(
		'<%1$s class="cb-heading %2$s">%3$s</%1$s>',
		tag_escape( $level ),
		esc_attr( $size ),
		wp_kses( $heading, $heading_allowed )
	);
	if ( $as_section ) {
		?>
			</div>
		</div>
	</div>
		<?php
	}
	?>
</div>
