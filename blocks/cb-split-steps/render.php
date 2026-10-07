<?php
/**
 * Block template for CB Split Steps.
 *
 * Sibling of CB Why Split without the stats grid: sticky headline/copy
 * column beside a numbered list of steps, each with an optional link.
 * Watermark uses the shared clamped-parallax module (section
 * data-watermark hook + measurable word span, no inline script) rather
 * than Why Split's older sticky + script pattern; background colour comes
 * from native supports.color, applied by get_block_wrapper_attributes().
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow   = $attributes['eyebrow'] ?? '';
$headline  = $attributes['headline'] ?? '';
$body      = $attributes['body'] ?? '';
$steps     = $attributes['steps'] ?? array();
$watermark = trim( $attributes['watermark'] ?? '' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);
$br_allowed       = array(
	'br' => array(),
);

$steps = array_filter(
	$steps,
	function ( $step ) {
		return ! empty( $step['title'] ) || ! empty( $step['body'] ) || ! empty( $step['link'] );
	}
);

$wrapper_args = array( 'class' => 'split-steps' );

if ( $watermark ) {
	$wrapper_args['data-watermark'] = '';
}

$wrapper_attributes = get_block_wrapper_attributes( $wrapper_args );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<?php
	if ( $watermark ) {
		?>
	<div class="split-steps-watermark" aria-hidden="true"><span><?php echo esc_html( $watermark ); ?></span></div>
		<?php
	}
	?>
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-5">
				<div class="split-steps-left">
					<?php
					if ( $eyebrow ) {
						?>
					<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
						<?php
					}
					if ( $headline ) {
						?>
					<h2 class="split-steps-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
						<?php
					}
					if ( $body ) {
						// RichText-era values carry their own markup; pre-conversion
						// plain-text values (literal newlines, no tags) are split
						// into paragraphs the same way until their first editor
						// save rewrites them as markup.
						if ( false !== strpos( $body, '<' ) ) {
							$body_html = wp_kses_post( $body );
						} else {
							$body_paras = array_filter(
								array_map( 'trim', preg_split( '/\n\s*\n/', $body ) ),
								function ( $para ) {
									return '' !== $para;
								}
							);
							$body_html  = '';
							foreach ( $body_paras as $para ) {
								$body_html .= '<p>' . nl2br( esc_html( $para ) ) . '</p>';
							}
						}
						?>
					<div class="split-steps-copy prose-md"><?php echo $body_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses_post() output, or rebuilt <p> wrappers around escaped text with nl2br() line breaks. ?></div>
						<?php
					}
					?>
				</div>
			</div>

			<div class="col-12 col-lg-7">
				<?php
				if ( $steps ) {
					?>
				<div class="split-steps-steps">
					<?php
					foreach ( $steps as $index => $step ) {
						$btitle = $step['title'] ?? '';
						$rbody  = $step['body'] ?? '';
						$link   = $step['link'] ?? '';
						?>
					<div class="split-steps-step">
						<div class="split-steps-step-num"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
						<div>
							<?php
							if ( $btitle ) {
								?>
							<h3 class="split-steps-step-title h6"><?php echo esc_html( $btitle ); ?></h3>
								<?php
							}
							if ( $rbody ) {
								?>
							<div class="split-steps-step-body"><?php echo nl2br( wp_kses( $rbody, $br_allowed ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses()'d, then nl2br() adds only <br>. ?></div>
								<?php
							}
							if ( $link ) {
								$link_text = $step['linkText'] ?? '';
								if ( '' === trim( $link_text ) ) {
									$link_text = $link;
								}
								$link_extra = ! empty( $step['linkTarget'] ) ? ' target="_blank" rel="noopener"' : '';
								?>
							<a class="split-steps-step-link" href="<?php echo esc_url( $link ); ?>"<?php echo $link_extra; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed literals built above, no user input. ?>><?php echo esc_html( $link_text ); ?> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
								<?php
							}
							?>
						</div>
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
		</div>
	</div>
</section>
