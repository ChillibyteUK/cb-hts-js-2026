<?php
/**
 * 404 template.
 *
 * @package cb-hts-js-2026
 */

get_header();
?>

<div class="container">
	<h1><?php esc_html_e( 'Page not found', 'cb-hts-js-2026' ); ?></h1>
	<p><?php esc_html_e( "The page you're looking for doesn't exist.", 'cb-hts-js-2026' ); ?></p>
</div>

<?php
get_footer();
