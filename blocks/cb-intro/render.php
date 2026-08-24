<?php
/**
 * Block template for CB Intro.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = $attributes['eyebrow'] ?? '';
$headline   = $attributes['headline'] ?? '';
$body       = $attributes['body'] ?? '';
$signature  = $attributes['signature'] ?? '';
$highlights = $attributes['highlights'] ?? '';

$headline_allowed  = array(
	'span' => array(),
	'br'   => array(),
);
$signature_allowed = array(
	'strong' => array(),
	'em'     => array(),
	'br'     => array(),
);

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'intro' ) );

$pillar_lines = array();
if ( $highlights ) {
	$pillar_lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\n|\r/', $highlights ) ) );
}
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="intro-inner">
			<div class="intro-col-head">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="intro-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				?>
			</div>
			<div class="intro-col-body">
				<?php
				if ( $body ) {
					?>
				<div class="intro-body"><?php echo wp_kses_post( $body ); ?></div>
					<?php
				}
				if ( $signature ) {
					?>
				<div class="intro-signature">
					<div><?php echo wp_kses( $signature, $signature_allowed ); ?></div>
				</div>
					<?php
				}
				if ( $pillar_lines ) {
					?>
				<div class="intro-pillars">
					<?php
					foreach ( $pillar_lines as $pillar ) {
						?>
					<span class="intro-pill">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12" /></svg>
						<?php echo esc_html( $pillar ); ?>
					</span>
						<?php
					}
					?>
				</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
