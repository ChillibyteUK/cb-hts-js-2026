<?php
/**
 * Header template.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<a class="visually-hidden" href="#main">Skip to content</a>
<?php wp_body_open(); ?>

<!-- HEADER-NAV:START -->
<?php
$cb_hts_js_2026_phone = cb_hts_js_2026_get_setting( 'phone' );
$cb_hts_js_2026_email = cb_hts_js_2026_get_setting( 'email' );

if ( $cb_hts_js_2026_phone || $cb_hts_js_2026_email ) {
	?>
	<div class="utility-bar">
		<div class="container d-flex align-items-center justify-content-between w-100 gap-2">
			<div class="d-flex align-items-center gap-4 fw-500">
				<?php
				if ( $cb_hts_js_2026_phone ) {
					?>
					<a href="tel:<?php echo esc_attr( parse_phone( $cb_hts_js_2026_phone ) ); ?>"><?php echo esc_html( $cb_hts_js_2026_phone ); ?></a>
					<?php
				}

				if ( $cb_hts_js_2026_phone && $cb_hts_js_2026_email ) {
					?>
					<div class="vr"></div>
					<?php
				}

				if ( $cb_hts_js_2026_email ) {
					?>
					<a href="mailto:<?php echo esc_attr( antispambot( $cb_hts_js_2026_email ) ); ?>"><?php echo antispambot( esc_html( $cb_hts_js_2026_email ) ); ?></a>
					<?php
				}

				if ( $cb_hts_js_2026_phone || $cb_hts_js_2026_email ) {
					?>
					<div class="vr"></div>
					<?php
				}
				?>
				<a href="https://hts-tentiq.com/eu-en/" target="_blank" rel="noopener">HTS-Tentiq Global &#8599;</a>
			</div>
		</div>
	</div>
	<?php
}
?>

<header id="masthead" class="sticky-top">
	<nav class="navbar container" aria-label="Primary navigation">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" aria-label="<?php bloginfo( 'name' ); ?> home">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/HTS_Logo.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
		</a>

		<button class="navbar-toggler" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="Toggle navigation">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path d="M2 5h16M2 10h16M2 15h16" />
			</svg>
		</button>

		<div class="navbar-collapse" id="primary-menu">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary_nav',
					'menu_class'     => 'navbar-nav',
					'container'      => false,
					'fallback_cb'    => false,
					'walker'         => new CB_HTS_JS_2026_Nav_Walker(),
				)
			);
			?>

			<div class="nav-cta">
				<a href="/configurator/" class="btn btn-outline-dark">Start Designing</a>
				<a href="/contact/" class="btn btn-primary">Get an Estimate</a>
			</div>
		</div>
	</nav>
</header>
<!-- HEADER-NAV:END -->

<main id="main">
