<?php
/**
 * Product post meta fields.
 *
 * Replaces the old ACF "CPT Product" field group:
 * - tag
 * - card_intro
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register product meta fields.
 *
 * @return void
 */
function cb_hts_js_2026_register_product_meta() {
	register_post_meta(
		'product',
		'tag',
		array(
			'single'            => true,
			'type'              => 'string',
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
			'default'           => '',
		)
	);

	register_post_meta(
		'product',
		'card_intro',
		array(
			'single'            => true,
			'type'              => 'string',
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_textarea_field',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
			'default'           => '',
		)
	);
}
add_action( 'init', 'cb_hts_js_2026_register_product_meta' );

/**
 * Add the product meta box.
 *
 * @return void
 */
function cb_hts_js_2026_add_product_meta_box() {
	add_meta_box(
		'cb_hts_js_2026_product_meta',
		'CPT Product',
		'cb_hts_js_2026_render_product_meta_box',
		'product',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'cb_hts_js_2026_add_product_meta_box' );

/**
 * Render the product meta box.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function cb_hts_js_2026_render_product_meta_box( $post ) {
	$tag        = get_post_meta( $post->ID, 'tag', true );
	$card_intro = get_post_meta( $post->ID, 'card_intro', true );

	wp_nonce_field( 'cb_hts_js_2026_save_product_meta', 'cb_hts_js_2026_product_meta_nonce' );
	?>
	<p>
		<label for="cb-hts-js-2026-tag"><strong><?php esc_html_e( 'Tag', 'cb-hts-js-2026' ); ?></strong></label>
		<input
			type="text"
			id="cb-hts-js-2026-tag"
			name="cb_hts_js_2026_product_meta[tag]"
			value="<?php echo esc_attr( $tag ); ?>"
			class="widefat"
		>
	</p>

	<p>
		<label for="cb-hts-js-2026-card-intro"><strong><?php esc_html_e( 'Card Intro', 'cb-hts-js-2026' ); ?></strong></label>
		<textarea
			id="cb-hts-js-2026-card-intro"
			name="cb_hts_js_2026_product_meta[card_intro]"
			rows="2"
			class="widefat"
		><?php echo esc_textarea( $card_intro ); ?></textarea>
	</p>
	<?php
}

/**
 * Save the product meta box fields.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function cb_hts_js_2026_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['cb_hts_js_2026_product_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cb_hts_js_2026_product_meta_nonce'] ) ), 'cb_hts_js_2026_save_product_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['cb_hts_js_2026_product_meta'] ) || ! is_array( $_POST['cb_hts_js_2026_product_meta'] ) ) {
		delete_post_meta( $post_id, 'tag' );
		delete_post_meta( $post_id, 'card_intro' );
		return;
	}

	$meta = wp_unslash( $_POST['cb_hts_js_2026_product_meta'] );

	$tag        = isset( $meta['tag'] ) ? sanitize_text_field( $meta['tag'] ) : '';
	$card_intro = isset( $meta['card_intro'] ) ? sanitize_textarea_field( $meta['card_intro'] ) : '';

	if ( '' !== $tag ) {
		update_post_meta( $post_id, 'tag', $tag );
	} else {
		delete_post_meta( $post_id, 'tag' );
	}

	if ( '' !== $card_intro ) {
		update_post_meta( $post_id, 'card_intro', $card_intro );
	} else {
		delete_post_meta( $post_id, 'card_intro' );
	}
}
add_action( 'save_post_product', 'cb_hts_js_2026_save_product_meta' );
