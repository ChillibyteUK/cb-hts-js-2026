<?php
/**
 * Block template for CB Applications Grid.
 *
 * Header from block attributes + cards pulled from the `application` CPT.
 * Each card links to the post if the post has body content; otherwise it
 * renders as a non-interactive tile.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = $attributes['eyebrow'] ?? '';
$headline = $attributes['headline'] ?? '';
$lede     = $attributes['lede'] ?? '';

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$apps = new WP_Query(
	array(
		'post_type'      => 'application',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'apps',
		'id'    => 'applications',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="apps-header row align-items-end">
			<div class="col-lg-5">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="apps-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				?>
			</div>
			<?php
			if ( $lede ) {
				?>
			<div class="col-lg-7">
				<div class="apps-intro prose-md"><?php echo wp_kses_post( $lede ); ?></div>
			</div>
				<?php
			}
			?>
		</div>

		<?php
		if ( $apps->have_posts() ) {
			?>
		<div class="apps-grid">
			<?php
			$i = 0;
			while ( $apps->have_posts() ) {
				$apps->the_post();
				++$i;
				$num      = str_pad( (string) $i, 2, '0', STR_PAD_LEFT );
				$has_url  = (bool) trim( get_the_content() );
				$html_tag = $has_url ? 'a' : 'div';
				$classes  = 'app-card';
				if ( $has_url ) {
					$classes .= ' app-card--linked';
				}
				?>
			<<?php echo esc_attr( $html_tag ); ?> class="<?php echo esc_attr( $classes ); ?>"<?php echo $has_url ? ' href="' . esc_url( get_permalink() ) . '"' : ''; ?>>
				<?php
				if ( has_post_thumbnail() ) {
					echo get_the_post_thumbnail(
						get_the_ID(),
						'large',
						array(
							'class' => 'app-card-img',
							'alt'   => esc_attr( get_the_title() ),
						)
					);
				}
				?>
				<div class="app-card-overlay" aria-hidden="true"></div>
				<div class="app-card-num"><?php echo esc_html( $num ); ?></div>
				<?php
				if ( $has_url ) {
					?>
				<div class="app-card-arrow" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M7 17L17 7M7 7h10v10" />
					</svg>
				</div>
					<?php
				}
				?>
				<div class="app-card-body">
					<div class="app-card-title"><?php echo esc_html( get_the_title() ); ?></div>
				</div>
			</<?php echo esc_attr( $html_tag ); ?>>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
			<?php
		}
		?>
	</div>
</section>
