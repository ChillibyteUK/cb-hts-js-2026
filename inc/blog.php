<?php
/**
 * Blog (Insights) helpers: breadcrumbs, in-page table of contents, and the
 * shared post card used by index.php and single.php's related-posts section.
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build breadcrumb items for the current singular view.
 *
 * @param int $post_id Current post ID.
 * @return array<int, array{label: string, url: string}>
 */
function cb_hts_js_2026_get_breadcrumbs( $post_id = 0 ) {
	$post_id     = $post_id ? (int) $post_id : get_the_ID();
	$breadcrumbs = array(
		array(
			'label' => __( 'Home', 'cb-hts-js-2026' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( ! $post_id ) {
		return $breadcrumbs;
	}

	if ( 'post' === get_post_type( $post_id ) ) {
		$blog_page_id = (int) get_option( 'page_for_posts' );

		if ( ! $blog_page_id ) {
			$blog_page    = get_page_by_path( 'insights' );
			$blog_page_id = $blog_page ? (int) $blog_page->ID : 0;
		}

		if ( $blog_page_id ) {
			$breadcrumbs[] = array(
				'label' => get_the_title( $blog_page_id ),
				'url'   => get_permalink( $blog_page_id ),
			);
		}
	} elseif ( is_page( $post_id ) || 'page' === get_post_type( $post_id ) ) {
		$ancestor_ids = array_reverse( get_post_ancestors( $post_id ) );

		foreach ( $ancestor_ids as $ancestor_id ) {
			$breadcrumbs[] = array(
				'label' => get_the_title( $ancestor_id ),
				'url'   => get_permalink( $ancestor_id ),
			);
		}
	}

	$breadcrumbs[] = array(
		'label' => get_the_title( $post_id ),
		'url'   => '',
	);

	return $breadcrumbs;
}

/**
 * Render breadcrumb markup with schema metadata.
 *
 * @param array  $breadcrumbs Breadcrumb items.
 * @param string $class_name  Wrapper class name.
 * @param bool   $container   Whether to wrap the list in its own .container
 *                            (pass false when already inside one — nested
 *                            .containers constrain each other's max-width).
 * @return void
 */
function cb_hts_js_2026_render_breadcrumbs( $breadcrumbs, $class_name = 'cb-breadcrumbs', $container = true ) {
	if ( empty( $breadcrumbs ) || ! is_array( $breadcrumbs ) || is_front_page() ) {
		return;
	}
	?>
	<nav class="<?php echo esc_attr( $class_name ); ?>" aria-label="Breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
		<?php
		if ( $container ) {
			?>
			<div class="container">
			<?php
		}
		?>
			<ol class="cb-breadcrumbs__list">
				<?php
				foreach ( $breadcrumbs as $index => $breadcrumb ) {
					?>
					<li class="cb-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
						<?php
						if ( ! empty( $breadcrumb['url'] ) ) {
							?>
							<a href="<?php echo esc_url( $breadcrumb['url'] ); ?>" itemprop="item"><span itemprop="name"><?php echo esc_html( $breadcrumb['label'] ); ?></span></a>
							<?php
						} else {
							?>
							<span itemprop="name" aria-current="page"><?php echo esc_html( $breadcrumb['label'] ); ?></span>
							<?php
						}
						?>
						<meta itemprop="position" content="<?php echo esc_attr( $index + 1 ); ?>">
					</li>
					<?php
				}
				?>
			</ol>
		<?php
		if ( $container ) {
			?>
			</div>
			<?php
		}
		?>
	</nav>
	<?php
}

/**
 * Extract an in-page table of contents from rendered HTML, injecting an
 * `id` onto each matched heading so the returned items' anchors actually
 * resolve. Ids are slugified from the heading text and de-duplicated
 * (second "Overview" becomes "overview-2", etc.) — headings are free text,
 * not guaranteed unique.
 *
 * DOMDocument over a regex: content is real (if messy) HTML by this point
 * (post the_content filter — blocks, shortcodes, wpautop already applied),
 * and a regex heading-matcher breaks the moment a heading contains inline
 * markup (a `<strong>`, an `<a>`, an emoji span) rather than plain text.
 *
 * @param string $html     Rendered HTML (e.g. apply_filters( 'the_content', $post->post_content )).
 * @param string $selector Heading tag to extract, e.g. 'h2'.
 * @return array{content: string, items: array<int, array{id: string, text: string}>}
 */
function cb_hts_js_2026_extract_toc( $html, $selector = 'h2' ) {
	if ( '' === trim( $html ) ) {
		return array(
			'content' => $html,
			'items'   => array(),
		);
	}

	$dom = new DOMDocument();
	// The <body> wrapper this implies is what the reconstruction step
	// below reads back out of — LIBXML_HTML_NOIMPLIED would suppress it
	// entirely, leaving nothing to read. Multi-byte content (emoji, curly
	// quotes) needs the XML-encoding declaration prepended to survive
	// loadHTML unmangled — the standard workaround.
	libxml_use_internal_errors( true );
	$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();

	$headings   = $dom->getElementsByTagName( $selector );
	$items      = array();
	$used_slugs = array();

	// Collect first (getElementsByTagName is a live NodeList — mutating
	// element attributes mid-iteration is fine, but this keeps that
	// separate from the counting/slugging logic below for clarity).
	$heading_nodes = array();
	foreach ( $headings as $heading ) {
		$heading_nodes[] = $heading;
	}

	foreach ( $heading_nodes as $heading ) {
		$text = trim( $heading->textContent );
		if ( '' === $text ) {
			continue;
		}

		// Skip headings that belong to an embedded block (e.g. the CB FAQs
		// block's own <h2>) rather than the article's own prose — those
		// blocks render as a <section>, unlike the flat markup core content
		// blocks (paragraph, heading, image, list...) produce.
		$in_section = false;
		for ( $ancestor = $heading->parentNode; $ancestor; $ancestor = $ancestor->parentNode ) {
			if ( 'section' === $ancestor->nodeName ) {
				$in_section = true;
				break;
			}
		}
		if ( $in_section ) {
			continue;
		}

		$slug = sanitize_title( $text );
		if ( '' === $slug ) {
			$slug = 'section';
		}

		if ( isset( $used_slugs[ $slug ] ) ) {
			++$used_slugs[ $slug ];
			$id = $slug . '-' . $used_slugs[ $slug ];
		} else {
			$used_slugs[ $slug ] = 1;
			$id                  = $slug;
		}

		$heading->setAttribute( 'id', $id );

		$items[] = array(
			'id'   => $id,
			'text' => $text,
		);
	}

	if ( empty( $items ) ) {
		return array(
			'content' => $html,
			'items'   => array(),
		);
	}

	$body        = $dom->getElementsByTagName( 'body' )->item( 0 );
	$new_content = '';
	foreach ( $body->childNodes as $child ) {
		$new_content .= $dom->saveHTML( $child );
	}

	return array(
		'content' => $new_content,
		'items'   => $items,
	);
}

/**
 * Estimate reading time from rendered post HTML, at 200 words per minute.
 * Always rounds up (and floors at 1) — "0 min read" reads as broken,
 * "1 min read" doesn't, even for a very short post.
 *
 * @param string $html Rendered HTML (e.g. apply_filters( 'the_content', $post->post_content )).
 * @return int Whole minutes, minimum 1.
 */
function cb_hts_js_2026_reading_time( $html ) {
	return max( 1, (int) estimate_reading_time_in_minutes( $html, 200 ) );
}

/**
 * Split a post's first paragraph off its content: the paragraph becomes the
 * hero lede (full text, no trim) and is removed from the body so it doesn't
 * render twice. Only a top-level paragraph qualifies — anything nested or
 * absent falls back to the excerpt for the lede and leaves the body alone.
 *
 * @param WP_Post $post Post object.
 * @return array{lede: string, content: string}
 */
function cb_hts_js_2026_lede_and_body( $post ) {
	if ( ! $post instanceof WP_Post ) {
		return array(
			'lede'    => '',
			'content' => '',
		);
	}

	$blocks = parse_blocks( $post->post_content );

	foreach ( $blocks as $index => $block ) {
		if ( 'core/paragraph' !== ( $block['blockName'] ?? '' ) ) {
			continue;
		}

		$text = trim( wp_strip_all_tags( render_block( $block ) ) );

		if ( '' === $text ) {
			continue;
		}

		unset( $blocks[ $index ] );

		return array(
			'lede'    => $text,
			'content' => serialize_blocks( array_values( $blocks ) ),
		);
	}

	return array(
		'lede'    => get_the_excerpt( $post ),
		'content' => $post->post_content,
	);
}

/**
 * Render one post card (image, title, date/reading-time meta, excerpt) —
 * shared by index.php's card grid and single.php's related posts, so the
 * two don't drift out of sync with each other.
 *
 * Expects the loop to already be on this post (called between the_post()
 * and the next iteration), same as template tags like the_title().
 *
 * The reading-time figure needs the_content filtered (so dynamic blocks
 * expand to their real word count), but that filter is what runs every
 * block's render_callback — including CB FAQs, which queues its Q&A pairs
 * onto the page's aggregated FAQPage schema as a side effect. Post cards
 * render OTHER posts, so without guarding this, any page carrying post
 * cards queues every FAQ from those other posts' bodies too. Snapshot and
 * restore the queue around the throwaway render so only FAQ blocks actually
 * placed on the current page contribute to its schema.
 *
 * @return void
 */
function cb_hts_js_2026_render_post_card() {
	global $faq_schema_items;
	$faq_schema_items_snapshot = $faq_schema_items ?? array();
	$minutes                   = cb_hts_js_2026_reading_time( apply_filters( 'the_content', get_the_content() ) );
	$faq_schema_items          = $faq_schema_items_snapshot;
	?>
	<a class="post-card" href="<?php the_permalink(); ?>">
		<?php
		if ( has_post_thumbnail() ) {
			// the_post_thumbnail() (not the_post_thumbnail_url()) so the <img>
			// gets explicit width/height/srcset from WordPress — without those,
			// the browser can't reserve the card's image space before the CSS
			// (which is what actually sizes it) has loaded, so the layout jumps
			// once it does. 'large' rather than 'medium' (300w) — at the 3-up
			// desktop card width this card can render past 300px, and 'medium'
			// upscaled past its own intrinsic size looks visibly soft.
			the_post_thumbnail( 'large', array( 'class' => 'post-card__image' ) );
		}
		?>
		<span class="post-card__title"><?php the_title(); ?></span>
		<span class="post-card__meta">
			<span class="post-card__meta-item">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
				<?php echo esc_html( get_the_date() ); ?>
			</span>
			<span class="post-card__meta-item">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
				<?php echo esc_html( $minutes ) . ' '; esc_html_e( 'min read', 'cb-hts-js-2026' ); ?>
			</span>
		</span>
		<span class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></span>
	</a>
	<?php
}
