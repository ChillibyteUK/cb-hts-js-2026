<?php
/**
 * Block template for CB Client Projects Gallery.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$images = $attributes['images'] ?? array();

if ( ! $images ) {
	return;
}

$layout     = $attributes['layout'] ?? 'mosaic';
$eyebrow    = $attributes['eyebrow'] ?? '';
$heading    = $attributes['heading'] ?? '';
$intro      = $attributes['intro'] ?? '';
$is_feature = 'feature' === $layout;

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

// Falls back to 'gallery' so "View the gallery" links work without the
// editor having to set an anchor on every case study.
$anchor = $attributes['anchor'] ?? '';
$anchor = $anchor ? $anchor : 'gallery';

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'client-gallery',
		'id'    => $anchor,
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<?php
		if ( $eyebrow ) {
			?>
		<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
			<?php
		}
		?>
		<div class="row pb-5">
			<div class="col-12 col-md-6">
				<?php
				if ( $heading ) {
					?>
				<h2 class="intro-headline h2"><?php echo wp_kses( $heading, $headline_allowed ); ?></h2>
					<?php
				}
				?>
			</div>
			<div class="col-12 col-md-6 offset-md-1">
				<?php
				if ( $intro ) {
					?>
				<div class="client-gallery-intro"><?php echo wp_kses_post( wpautop( $intro ) ); ?></div>
					<?php
				}
				?>
			</div>
		</div>

		<div class="client-gallery-grid<?php echo $is_feature ? ' client-gallery-grid--feature' : ''; ?>">
			<?php
			if ( $is_feature ) {
				foreach ( $images as $i => $image_id ) {
					$caption = wp_get_attachment_caption( $image_id );
					$classes = array( 'client-gallery-item', 'client-gallery-item--feature-tile' );

					if ( 0 === $i ) {
						$classes[] = 'client-gallery-item--lead';
					}
					?>
			<a
				href="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>"
				class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
				data-glightbox="<?php echo $caption ? esc_attr( 'title: ' . $caption ) : 'type: image'; ?>"
			>
					<?php
					echo wp_get_attachment_image( $image_id, 'medium_large', false, array( 'loading' => 0 === $i ? 'eager' : 'lazy' ) );
					if ( $caption ) {
						?>
				<span class="client-gallery-item-caption"><?php echo esc_html( $caption ); ?></span>
						<?php
					}
					?>
			</a>
					<?php
				}
			} else {
				foreach ( $images as $i => $image_id ) {
					$group    = intdiv( $i, 5 );
					$pos      = $i % 5;
					$row_base = $group * 3 + 1;
					$classes  = array( 'client-gallery-item' );
					$style    = '';

					if ( 0 === $group % 2 ) {
						// Pattern A: large block left, two stacked right, then wide + single.
						switch ( $pos ) {
							case 0:
								$style = "grid-column: 1 / 3; grid-row: {$row_base} / " . ( $row_base + 2 ) . ';';
								break;
							case 1:
								$style     = "grid-column: 3 / 4; grid-row: {$row_base} / " . ( $row_base + 1 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
							case 2:
								$style     = 'grid-column: 3 / 4; grid-row: ' . ( $row_base + 1 ) . ' / ' . ( $row_base + 2 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
							case 3:
								$style = 'grid-column: 1 / 3; grid-row: ' . ( $row_base + 2 ) . ' / ' . ( $row_base + 3 ) . ';';
								break;
							case 4:
								$style     = 'grid-column: 3 / 4; grid-row: ' . ( $row_base + 2 ) . ' / ' . ( $row_base + 3 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
						}
					} else {
						// Pattern B (flipped): two stacked left, large block right, then wide + single.
						switch ( $pos ) {
							case 0:
								$style     = "grid-column: 1 / 2; grid-row: {$row_base} / " . ( $row_base + 1 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
							case 1:
								$style = "grid-column: 2 / 4; grid-row: {$row_base} / " . ( $row_base + 2 ) . ';';
								break;
							case 2:
								$style     = 'grid-column: 1 / 2; grid-row: ' . ( $row_base + 1 ) . ' / ' . ( $row_base + 2 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
							case 3:
								$style = 'grid-column: 1 / 3; grid-row: ' . ( $row_base + 2 ) . ' / ' . ( $row_base + 3 ) . ';';
								break;
							case 4:
								$style     = 'grid-column: 3 / 4; grid-row: ' . ( $row_base + 2 ) . ' / ' . ( $row_base + 3 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
						}
					}
					?>
			<a
				href="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>"
				class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
				style="<?php echo esc_attr( $style ); ?>"
				data-glightbox="type: image"
			>
					<?php echo wp_get_attachment_image( $image_id, 'medium_large', false, array( 'loading' => 'lazy' ) ); ?>
			</a>
					<?php
				}
			}
			?>
		</div>
	</div>
</section>

<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	if (typeof GLightbox !== 'undefined') {
		GLightbox({ selector: '.client-gallery-item' });
	}
});
</script>
		<?php
	},
	999
);
