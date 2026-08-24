<?php
/**
 * Block template for CB Text Stats.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$variant     = $attributes['variant'] ?? 'dark';
$eyebrow     = $attributes['eyebrow'] ?? '';
$headline    = $attributes['headline'] ?? '';
$body        = $attributes['body'] ?? '';
$link_text   = $attributes['linkText'] ?? '';
$link_url    = $attributes['linkUrl'] ?? '';
$link_target = ! empty( $attributes['linkTarget'] );
$stats       = $attributes['stats'] ?? array();

$section_classes = 'parent';
if ( 'quote' === $variant ) {
	$section_classes .= ' parent--quote';
}

$eyebrow_class = 'quote' === $variant ? 'eyebrow' : 'eyebrow eyebrow--light';
$link_class    = 'quote' === $variant ? 'btn btn-outline-dark' : 'btn btn-outline-light';

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$stat_value_allowed = array(
	'sup' => array(),
	'br'  => array(),
);

$br_allowed = array(
	'br' => array(),
);

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $section_classes ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="parent-inner row align-items-center">
			<div class="col-lg-7">
				<?php
				if ( $eyebrow ) {
					?>
					<div class="<?php echo esc_attr( $eyebrow_class ); ?>"><?php echo esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
					<h2 class="parent-h2 h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				if ( $body ) {
					?>
					<div class="parent-body prose-md"><?php echo nl2br( esc_html( $body ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
					<?php
				}
				if ( $link_url ) {
					?>
					<a
						href="<?php echo esc_url( $link_url ); ?>"
						<?php echo $link_target ? ' target="_blank" rel="noopener"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static string, not user input. ?>
						class="<?php echo esc_attr( $link_class ); ?>"
					>
						<?php echo esc_html( $link_text ? $link_text : 'Learn More' ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
							<path d="M5 12h14M12 5l7 7-7 7" />
						</svg>
					</a>
					<?php
				}
				?>
			</div>

			<div class="col-lg-5">
				<?php
				if ( $stats ) {
					?>
					<div class="parent-stats">
						<?php
						foreach ( $stats as $stat ) {
							?>
							<div class="parent-stat">
								<?php
								if ( ! empty( $stat['value'] ) ) {
									?>
									<div class="parent-stat-num"><?php echo wp_kses( $stat['value'], $stat_value_allowed ); ?></div>
									<?php
								}
								if ( ! empty( $stat['label'] ) ) {
									?>
									<div class="parent-stat-label"><?php echo wp_kses( $stat['label'], $br_allowed ); ?></div>
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
	</div>
</section>
