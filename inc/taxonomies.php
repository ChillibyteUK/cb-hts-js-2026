<?php
/**
 * Custom taxonomies — ported from cb-hts2026 (inc/cb-taxonomies.php) as part
 * of the ACF -> native block migration. Args unchanged from the source. Note
 * both taxonomies attach only to `project`, not `application` — `product`
 * has no taxonomy at all, matching the source.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the application_cat and project_cat taxonomies.
 *
 * @return void
 */
function cb_hts_js_2026_register_taxonomies() {

	register_taxonomy(
		'application_cat',
		array( 'project' ),
		array(
			'labels'             => array(
				'name'          => 'Applications',
				'singular_name' => 'Application',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'rewrite'            => array(
				'slug'         => 'application-cat',
				'with_front'   => false,
				'hierarchical' => true,
			),
			'rest_base'          => 'application_cat',
		)
	);

	register_taxonomy(
		'project_cat',
		array( 'project' ),
		array(
			'labels'             => array(
				'name'          => 'Project Categories',
				'singular_name' => 'Project Category',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'rewrite'            => array(
				'slug'         => 'project-cat',
				'with_front'   => false,
				'hierarchical' => true,
			),
			'rest_base'          => 'project_cat',
		)
	);
}
add_action( 'init', 'cb_hts_js_2026_register_taxonomies' );
