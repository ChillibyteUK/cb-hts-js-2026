<?php
/**
 * Block template for CB Why Split.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = $attributes['eyebrow'] ?? '';
$headline = $attributes['headline'] ?? '';
$body     = $attributes['body'] ?? '';
$stats    = $attributes['stats'] ?? array();
$reasons  = $attributes['reasons'] ?? array();

$headline_allowed   = array(
	'span' => array(),
	'br'   => array(),
);
$stat_value_allowed = array(
	'sup' => array(),
	'br'  => array(),
);
$br_allowed         = array(
	'br' => array(),
);

$stats = array_filter(
	$stats,
	function ( $stat ) {
		return ! empty( $stat['value'] ) || ! empty( $stat['label'] );
	}
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'why-split',
		'id'    => 'why',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="why-split-watermark" aria-hidden="true"></div>
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-5">
				<div class="why-split-left">
					<?php
					if ( $eyebrow ) {
						?>
					<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
						<?php
					}
					if ( $headline ) {
						?>
					<h2 class="why-split-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
						<?php
					}
					if ( $body ) {
						?>
					<div class="why-split-copy prose-md"><?php echo nl2br( esc_html( $body ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
						<?php
					}
					if ( $stats ) {
						?>
					<div class="why-split-stats">
						<?php
						foreach ( $stats as $stat ) {
							$value = $stat['value'] ?? '';
							$label = $stat['label'] ?? '';
							?>
						<div>
							<?php
							if ( $value ) {
								?>
							<div class="why-split-stat-value"><?php echo wp_kses( $value, $stat_value_allowed ); ?></div>
								<?php
							}
							if ( $label ) {
								?>
							<div class="why-split-stat-label"><?php echo nl2br( wp_kses( $label, $br_allowed ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses()'d, then nl2br() adds only <br>. ?></div>
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

			<div class="col-12 col-lg-7">
				<?php
				if ( $reasons ) {
					?>
				<div class="why-split-reasons">
					<?php
					foreach ( $reasons as $index => $reason ) {
						$btitle = $reason['title'] ?? '';
						$rbody  = $reason['body'] ?? '';
						?>
					<div class="why-split-reason">
						<div class="why-split-reason-num"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
						<div>
							<?php
							if ( $btitle ) {
								?>
							<h3 class="why-split-reason-title h6"><?php echo esc_html( $btitle ); ?></h3>
								<?php
							}
							if ( $rbody ) {
								?>
							<div class="why-split-reason-body"><?php echo nl2br( wp_kses( $rbody, $br_allowed ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses()'d, then nl2br() adds only <br>. ?></div>
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
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
	if (
		!('IntersectionObserver' in window) ||
		window.matchMedia('(prefers-reduced-motion: reduce)').matches
	) {
		return;
	}

	var section = document.getElementById('why');

	if (!section) {
		return;
	}

	var observer = new IntersectionObserver(
		function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				section.classList.add('is-in-view');
				observer.disconnect();
			});
		},
		{
			threshold: 0.2,
			rootMargin: '0px 0px -10% 0px',
		}
	);

	observer.observe(section);
});
</script>
