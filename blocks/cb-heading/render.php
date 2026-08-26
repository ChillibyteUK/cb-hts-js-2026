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

$level = ! empty( $attributes['level'] ) ? strtolower( $attributes['level'] ) : 'h2';
if ( ! in_array( $level, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
	$level = 'h2';
}

$size = ! empty( $attributes['size'] ) ? strtolower( $attributes['size'] ) : 'h3';
if ( ! in_array( $size, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
	$size = 'h3';
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'cb-heading ' . $size ) );

printf(
	'<%1$s %2$s>%3$s</%1$s>',
	tag_escape( $level ),
	$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes.
	wp_kses(
		$heading,
		array(
			'span' => array(),
			'br'   => array(),
		)
	)
);
