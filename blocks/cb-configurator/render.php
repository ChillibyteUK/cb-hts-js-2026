<?php
/**
 * Block template for CB Configurator.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$ctag       = $attributes['tag'] ?? '';
$headline   = $attributes['headline'] ?? '';
$intro      = $attributes['intro'] ?? '';
$features   = $attributes['features'] ?? '';
$cta_text   = $attributes['ctaText'] ?? '';
$cta_url    = $attributes['ctaUrl'] ?? '';
$cta_target = ! empty( $attributes['ctaTarget'] );
$image_id   = $attributes['imageId'] ?? 0;

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);
$features_allowed = array(
	'strong' => array(),
	'em'     => array(),
	'br'     => array(),
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'config',
		'id'    => 'configurator',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="config-inner row align-items-center">
			<div class="col-lg-6">
				<?php
				if ( $ctag ) {
					?>
				<span class="config-tag"><?php echo esc_html( $ctag ); ?></span>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="config-h2 h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				if ( $intro ) {
					?>
				<div class="config-body prose-md"><?php echo wp_kses_post( $intro ); ?></div>
					<?php
				}
				if ( $features ) {
					?>
				<ul class="config-features">
					<?php
					foreach ( preg_split( '/\r\n|\n|\r/', $features ) as $feature ) {
						$feature = trim( $feature );
						if ( '' === $feature ) {
							continue;
						}
						?>
					<li><?php echo wp_kses( $feature, $features_allowed ); ?></li>
						<?php
					}
					?>
				</ul>
					<?php
				}
				if ( $cta_url ) {
					?>
				<a
					href="<?php echo esc_url( $cta_url ); ?>"
					<?php echo $cta_target ? ' target="_blank" rel="noopener"' : ''; ?>
					class="btn btn-primary w-100 w-md-auto"
				>
					<?php echo esc_html( $cta_text ? $cta_text : 'Launch the configurator' ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
						<path d="M5 12h14M12 5l7 7-7 7" />
					</svg>
				</a>
					<?php
				}
				?>
			</div>
			<?php
			if ( $image_id ) {
				?>
			<div class="col-lg-6">
				<div class="config-img">
					<?php
					echo wp_get_attachment_image(
						$image_id,
						'large',
						false,
						array( 'alt' => esc_attr( $attributes['imageAlt'] ?? '' ) )
					);
					?>
				</div>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
