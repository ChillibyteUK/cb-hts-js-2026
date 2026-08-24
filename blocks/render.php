<?php
/**
 * Block template for Test Layout Block.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$lede = $attributes['lede'] ?? '';
$cta_primary_text = $attributes['ctaPrimaryText'] ?? '';
$cta_primary_url = $attributes['ctaPrimaryUrl'] ?? '';
$cta_primary_target = ! empty( $attributes['ctaPrimaryTarget'] );
$cta_secondary_text = $attributes['ctaSecondaryText'] ?? '';
$cta_secondary_url = $attributes['ctaSecondaryUrl'] ?? '';
$badge_number = $attributes['badgeNumber'] ?? '';
$badge_suffix = $attributes['badgeSuffix'] ?? '';
$badge_label = $attributes['badgeLabel'] ?? '';
$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'container' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<?php if ( $lede ) { ?>
		<p><?php echo esc_html( $lede ); ?></p>
	<?php } ?>
	<?php if ( $cta_primary_url ) { ?>
		<a href="<?php echo esc_url( $cta_primary_url ); ?>"<?php if ( $cta_primary_target ) { ?> target="_blank" rel="noopener"<?php } ?>><?php echo esc_html( $cta_primary_text ? $cta_primary_text : $cta_primary_url ); ?></a>
	<?php } ?>
	<?php if ( $cta_secondary_url ) { ?>
		<a href="<?php echo esc_url( $cta_secondary_url ); ?>"><?php echo esc_html( $cta_secondary_text ? $cta_secondary_text : $cta_secondary_url ); ?></a>
	<?php } ?>
	<?php if ( $badge_number ) { ?>
		<p><?php echo esc_html( $badge_number ); ?></p>
	<?php } ?>
	<?php if ( $badge_suffix ) { ?>
		<p><?php echo esc_html( $badge_suffix ); ?></p>
	<?php } ?>
	<?php if ( $badge_label ) { ?>
		<div><?php echo nl2br( esc_html( $badge_label ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
	<?php } ?></section>
