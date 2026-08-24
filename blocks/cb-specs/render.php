<?php
/**
 * Block template for CB Specs.
 *
 * The source computes a `$bg` "has-{color}-background-color" class from
 * ACF Blocks' background-color support but never actually applies it
 * anywhere in its markup — dead code in the source, not ported here (see
 * the project's standing rule on not carrying forward latent source bugs).
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = $attributes['eyebrow'] ?? '';
$headline = $attributes['headline'] ?? '';
$intro    = $attributes['intro'] ?? '';
$rows     = $attributes['rows'] ?? '';

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$rows = array_filter(
	$rows,
	function ( $row ) {
		return ! empty( $row['label'] ) || ! empty( $row['value'] );
	}
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'specs',
		'id'    => 'specs',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="specs-watermark" aria-hidden="true"></div>
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-5">
				<div class="specs-left">
					<?php
					if ( $eyebrow ) {
						?>
					<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
						<?php
					}
					if ( $headline ) {
						?>
					<h2 class="specs-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
						<?php
					}
					if ( $intro ) {
						?>
					<div class="specs-intro prose-md"><?php echo nl2br( esc_html( $intro ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
						<?php
					}
					?>
				</div>
			</div>

			<div class="col-12 col-lg-7">
				<?php
				if ( $rows ) {
					?>
				<table class="specs-table">
					<tbody>
						<?php
						foreach ( $rows as $row ) {
							?>
						<tr>
							<td class="specs-label"><?php echo esc_html( $row['label'] ?? '' ); ?></td>
							<td class="specs-value"><?php echo nl2br( esc_html( $row['value'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></td>
						</tr>
							<?php
						}
						?>
					</tbody>
				</table>
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

	var section = document.getElementById('specs');

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
