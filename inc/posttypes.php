<?php
/**
 * Custom post types — ported from cb-hts2026 (inc/cb-posttypes.php) as part
 * of the ACF -> native block migration. Args are unchanged from the source:
 * all three have has_archive false, since single-CPT "archive" pages are
 * ordinary Pages instead (see the nav-highlighting logic in inc/enqueue.php
 * or wherever the primary nav is built).
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the application, product, and project post types.
 *
 * @return void
 */
function cb_hts_js_2026_register_post_types() {

	register_post_type(
		'application',
		array(
			'labels'              => array(
				'name'               => 'Applications',
				'singular_name'      => 'Application',
				'add_new_item'       => 'Add New Application',
				'edit_item'          => 'Edit Application',
				'new_item'           => 'New Application',
				'view_item'          => 'View Application',
				'search_items'       => 'Search Applications',
				'not_found'          => 'No applications found',
				'not_found_in_trash' => 'No applications in trash',
			),
			'has_archive'         => false,
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-portfolio',
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'rewrite'             => array( 'slug' => 'applications' ),
		)
	);

	register_post_type(
		'product',
		array(
			'labels'              => array(
				'name'               => 'Products',
				'singular_name'      => 'Product',
				'add_new_item'       => 'Add New Product',
				'edit_item'          => 'Edit Product',
				'new_item'           => 'New Product',
				'view_item'          => 'View Product',
				'search_items'       => 'Search Products',
				'not_found'          => 'No products found',
				'not_found_in_trash' => 'No products in trash',
			),
			'has_archive'         => false,
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-cart',
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'rewrite'             => array(
				'slug'       => 'products',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		'project',
		array(
			'labels'              => array(
				'name'               => 'Projects',
				'singular_name'      => 'Project',
				'add_new_item'       => 'Add New Project',
				'edit_item'          => 'Edit Project',
				'new_item'           => 'New Project',
				'view_item'          => 'View Project',
				'search_items'       => 'Search Projects',
				'not_found'          => 'No projects found',
				'not_found_in_trash' => 'No projects in trash',
			),
			'has_archive'         => false,
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-clipboard',
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'rewrite'             => array(
				'slug'       => 'projects',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'cb_hts_js_2026_register_post_types' );

/**
 * Flush rewrite rules on theme activation so the /products, /applications,
 * /projects slugs work immediately rather than needing a manual permalink
 * resave.
 *
 * @return void
 */
function cb_hts_js_2026_flush_rewrites() {
	cb_hts_js_2026_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'cb_hts_js_2026_flush_rewrites' );

/**
 * Serve page.php for singular views of any custom post type that has no
 * single-{post_type}.php of its own, instead of falling back to the
 * generic single.php. CPT content in this theme is built from the same
 * blocks as pages — single.php's auto-printed <h1>/date and
 * .container/<article> wrapper are built for classic blog posts, not
 * block-built layouts, and would clash with a block's own heading (e.g.
 * a hero block).
 *
 * Only steps in when WordPress's own template hierarchy has already fallen
 * through to the generic single.php ($template's basename) — application,
 * product, and project can still each get their own single-{post_type}.php
 * if one is added later, and this filter won't touch it. Regular WP posts
 * are untouched too (is_singular( 'post' ) is excluded), so blog posts keep
 * using single.php as normal.
 *
 * @param string $template Template path WordPress would otherwise use.
 * @return string
 */
function cb_hts_js_2026_use_page_template_for_cpts( $template ) {
	if ( is_singular() && ! is_page() && ! is_singular( 'post' ) && 'single.php' === basename( $template ) ) {
		$page_template = get_query_template( 'page' );

		if ( $page_template ) {
			return $page_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'cb_hts_js_2026_use_page_template_for_cpts' );

/**
 * Which ordinary Page each has_archive-false CPT treats as its "parent" in
 * the primary nav, since none of them have a real archive of their own.
 * Filterable so a project can remap without editing this file directly.
 *
 * @return array Post type => page slug.
 */
function cb_hts_js_2026_nav_cpt_parent_pages() {
	return apply_filters(
		'cb_hts_js_2026_nav_cpt_parent_pages',
		array(
			'product' => 'products',
			'project' => 'projects',
		)
	);
}

/**
 * Marks the parent menu page as current-menu-parent when viewing a singular
 * product/project — otherwise the primary nav has nothing highlighted at
 * all on those pages, since the CPT itself isn't in the menu.
 *
 * @param array    $classes Menu item classes.
 * @param WP_Post  $item    Menu item data object.
 * @param stdClass $args    wp_nav_menu() arguments.
 * @return array
 */
function cb_hts_js_2026_nav_highlight_cpt_parent( $classes, $item, $args ) {
	if ( ! isset( $args->theme_location ) || 'primary_nav' !== $args->theme_location ) {
		return $classes;
	}

	if ( ! is_singular() || 'page' !== $item->object ) {
		return $classes;
	}

	$map       = cb_hts_js_2026_nav_cpt_parent_pages();
	$post_type = get_post_type();

	if ( ! isset( $map[ $post_type ] ) ) {
		return $classes;
	}

	$parent = get_page_by_path( $map[ $post_type ] );

	if ( ! $parent || (int) $item->object_id !== (int) $parent->ID ) {
		return $classes;
	}

	$classes[] = 'current-menu-parent';
	$classes[] = 'current_page_parent';

	return $classes;
}
add_filter( 'nav_menu_css_class', 'cb_hts_js_2026_nav_highlight_cpt_parent', 10, 3 );
