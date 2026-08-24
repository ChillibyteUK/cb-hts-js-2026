<?php
/**
 * Page template.
 *
 * Most projects build page layouts from native blocks rather than the_content()
 * directly — override this per project as needed.
 *
 * @package cb-hts-js-2026
 */

get_header();
?>

<?php
while ( have_posts() ) {
	the_post();
	the_content();
}
?>

<?php
get_footer();
