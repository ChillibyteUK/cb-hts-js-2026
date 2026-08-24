<?php
/**
 * Block template for CB Downloads.
 *
 * Grid of downloadable documents — data sheets, certifications, brochures.
 * File type and size are read from the attachment rather than typed by hand.
 * Background/text colour come from this block's native Gutenberg color
 * support (unlike CB Specs' dead `$bg` computation in the source, this one
 * is genuinely applied there, so it's a real port, not a fabricated
 * feature).
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = $attributes['eyebrow'] ?? '';
$headline = $attributes['headline'] ?? '';
$intro    = $attributes['intro'] ?? '';
$items    = $attributes['items'] ?? array();

if ( ! $items ) {
	return;
}

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$section_id = $attributes['anchor'] ?? '';
$section_id = $section_id ? $section_id : 'downloads';

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'downloads',
		'id'    => $section_id,
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<?php
		// Skip the header row entirely when empty, so it can't leave a gap.
		if ( $eyebrow || $headline || $intro ) {
			?>
		<div class="downloads-header row align-items-end">
			<?php
			if ( $eyebrow || $headline ) {
				?>
			<div class="col-12 col-lg-7">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="downloads-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				?>
			</div>
				<?php
			}
			if ( $intro ) {
				?>
			<div class="col-12 col-lg-5">
				<div class="downloads-intro prose-md"><?php echo nl2br( esc_html( $intro ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
			</div>
				<?php
			}
			?>
		</div>
			<?php
		}
		?>

		<ul class="downloads-grid">
			<?php
			foreach ( $items as $item ) {
				$file_id = ! empty( $item['file'] ) ? absint( $item['file'] ) : 0;

				if ( ! $file_id ) {
					continue;
				}

				$file_url = wp_get_attachment_url( $file_id );

				if ( ! $file_url ) {
					continue;
				}

				$btitle = ! empty( $item['title'] ) ? $item['title'] : '';
				$meta   = $item['meta'] ?? '';

				if ( '' === $btitle ) {
					$attachment_title = get_the_title( $file_id );
					$btitle           = $attachment_title ? $attachment_title : basename( $file_url );
				}

				$ext           = strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) );
				$attached_file = get_attached_file( $file_id );
				$size          = $attached_file && file_exists( $attached_file ) ? size_format( filesize( $attached_file ) ) : '';
				$spec          = array_filter( array( $ext, $size ) );

				// PDFs get a page-one cover image once Imagick has generated one;
				// anything without a preview falls back to the extension badge.
				$cover = wp_get_attachment_image(
					$file_id,
					'thumbnail',
					false,
					array(
						'class'   => 'downloads-item-cover',
						'alt'     => '',
						'loading' => 'lazy',
					)
				);
				?>
			<li class="downloads-item">
				<a class="downloads-link" href="<?php echo esc_url( $file_url ); ?>" download>
					<span class="downloads-item-icon<?php echo $cover ? ' downloads-item-icon--cover' : ''; ?>" aria-hidden="true">
						<?php
						if ( $cover ) {
							echo wp_kses_post( $cover );
						} elseif ( $ext ) {
							?>
						<span class="downloads-item-ext"><?php echo esc_html( $ext ); ?></span>
							<?php
						}
						?>
					</span>
					<span class="downloads-item-text">
						<span class="downloads-item-title"><?php echo esc_html( $btitle ); ?></span>
						<?php
						if ( $meta ) {
							?>
						<span class="downloads-item-meta"><?php echo esc_html( $meta ); ?></span>
							<?php
						}
						if ( $spec ) {
							?>
						<span class="downloads-item-spec"><?php echo esc_html( implode( ' · ', $spec ) ); ?></span>
							<?php
						}
						?>
					</span>
					<span class="downloads-item-arrow" aria-hidden="true"></span>
					<span class="visually-hidden"><?php esc_html_e( 'Download', 'cb-hts-js-2026' ); ?></span>
				</a>
			</li>
				<?php
			}
			?>
		</ul>
	</div>
</section>
