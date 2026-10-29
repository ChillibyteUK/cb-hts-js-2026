<?php
/**
 * Block patterns — full-page recipes for Product, Application and Case Study
 * (project) pages. The pattern files live in patterns/*.php and are picked up
 * by WordPress core automatically; this file only registers their category.
 * Each pattern also declares `Post Types` + `Block Types: core/post-content`
 * in its header, so it is offered as a starter layout when a new post of that
 * type is created.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the pattern category the page recipes sit under.
 *
 * @return void
 */
function cb_hts_js_2026_register_pattern_categories() {
	register_block_pattern_category(
		'cb-hts-js-2026-pages',
		array(
			'label'       => __( 'HTS Page Recipes', 'cb-hts-js-2026' ),
			'description' => __( 'Full-page block layouts for products, applications and case studies.', 'cb-hts-js-2026' ),
		)
	);
}
add_action( 'init', 'cb_hts_js_2026_register_pattern_categories' );
