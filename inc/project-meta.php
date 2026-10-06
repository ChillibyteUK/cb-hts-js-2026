<?php
/**
 * Project post meta fields.
 *
 * - product_used_id: the product featured by a project's CB Product Used
 *   block, mirrored here as structured post meta so WP_Query can filter on
 *   it (block attributes inside post_content are not queryable). Synced
 *   one-way, block -> meta, on save — the block stays the single editing
 *   UI, this meta is never edited directly.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register project meta fields.
 *
 * @return void
 */
function cb_hts_js_2026_register_project_meta() {
	register_post_meta(
		'project',
		'product_used_id',
		array(
			'single'            => true,
			'type'              => 'number',
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
			'default'           => 0,
		)
	);
}
add_action( 'init', 'cb_hts_js_2026_register_project_meta' );

/**
 * Find the first CB Product Used block in a parsed block tree, depth-first.
 *
 * @param array $blocks Parsed blocks (parse_blocks() output).
 * @return array|null The block array, or null when no Product Used block exists.
 */
function cb_hts_js_2026_find_product_used_block( $blocks ) {
	foreach ( $blocks as $block ) {
		if ( ! is_array( $block ) ) {
			continue;
		}

		if ( 'cb-hts-js-2026/cb-product-used' === ( $block['blockName'] ?? '' ) ) {
			return $block;
		}

		if ( ! empty( $block['innerBlocks'] ) ) {
			$found = cb_hts_js_2026_find_product_used_block( $block['innerBlocks'] );

			if ( is_array( $found ) ) {
				return $found;
			}
		}
	}

	return null;
}

/**
 * Mirror a project's CB Product Used block selection into post meta.
 *
 * Runs on save: the first Product Used block with a chosen product wins;
 * no block (or no selection) clears the meta so the grid filter can never
 * go stale behind removed content.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function cb_hts_js_2026_sync_product_used_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( 'project' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post = get_post( $post_id );

	if ( ! $post instanceof WP_Post ) {
		return;
	}

	$product_id = 0;
	$block      = cb_hts_js_2026_find_product_used_block( parse_blocks( $post->post_content ) );

	if ( is_array( $block ) ) {
		$product_id = absint( $block['attrs']['productId'] ?? 0 );
	}

	if ( $product_id ) {
		update_post_meta( $post_id, 'product_used_id', $product_id );
	} else {
		delete_post_meta( $post_id, 'product_used_id' );
	}
}
add_action( 'save_post_project', 'cb_hts_js_2026_sync_product_used_meta' );
