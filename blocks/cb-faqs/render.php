<?php
/**
 * Block template for CB FAQs.
 *
 * Source also computes a background/text-derived "light-lines"/"dark-lines"
 * class via a regex against the Gutenberg colour slug — not ported, since
 * neither class is defined anywhere in the source's own CSS at all (a
 * completely dead feature, unlike CB Downloads' genuinely-applied colour
 * support, which *is* ported here the same native way).
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cb_hts_js_2026_faqs_add_schema_items' ) ) {
	/**
	 * Collect FAQ items and output a single FAQPage schema in wp_footer.
	 *
	 * @param array $items Array of items with 'question' and 'answer' keys.
	 * @return void
	 */
	function cb_hts_js_2026_faqs_add_schema_items( array $items ) {
		static $all_items = array();
		static $hooked     = false;

		foreach ( $items as $item ) {
			$all_items[] = $item;
		}

		if ( ! $hooked ) {
			$hooked = true;
			add_action(
				'wp_footer',
				function () use ( &$all_items ) {
					if ( empty( $all_items ) ) {
						return;
					}

					$entities = array_map(
						function ( $item ) {
							return array(
								'@type'          => 'Question',
								'name'           => $item['question'],
								'acceptedAnswer' => array(
									'@type' => 'Answer',
									'text'  => $item['answer'],
								),
							);
						},
						$all_items
					);

					$schema = array(
						'@context'   => 'https://schema.org',
						'@type'      => 'FAQPage',
						'mainEntity' => $entities,
					);

					echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
				}
			);
		}
	}
}

$headline = $attributes['headline'] ?? '';
$intro    = $attributes['intro'] ?? '';
$faqs     = $attributes['faqs'] ?? array();

$faqs = array_filter(
	$faqs,
	function ( $faq ) {
		return ! empty( $faq['question'] ) || ! empty( $faq['answer'] );
	}
);

if ( ! $faqs ) {
	return;
}

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);
$br_allowed        = array(
	'br' => array(),
);

$schema_items = array();
foreach ( $faqs as $faq ) {
	$question = isset( $faq['question'] ) ? wp_strip_all_tags( $faq['question'] ) : '';
	$answer   = isset( $faq['answer'] ) ? wp_strip_all_tags( $faq['answer'] ) : '';

	if ( '' === $question || '' === $answer ) {
		continue;
	}

	$schema_items[] = array(
		'question' => $question,
		'answer'   => $answer,
	);
}
cb_hts_js_2026_faqs_add_schema_items( $schema_items );

$section_id   = $attributes['anchor'] ?? '';
$section_id   = $section_id ? $section_id : wp_unique_id( 'cb-faqs-' );
$accordion_id = wp_unique_id( 'faq-accordion-' );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'faq dark-lines',
		'id'    => $section_id,
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="faq-watermark" aria-hidden="true"></div>
	<div class="container">
		<div class="faq-header">
			<div class="eyebrow eyebrow--plain"><?php esc_html_e( 'FAQs', 'cb-hts-js-2026' ); ?></div>
			<?php
			if ( $headline ) {
				?>
			<h2 class="faq-headline h2"><?php echo wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php
			}
			if ( $intro ) {
				?>
			<p class="faq-sub"><?php echo nl2br( wp_kses( $intro, $br_allowed ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses()'d, then nl2br() adds only <br>. ?></p>
				<?php
			}
			?>
		</div>
		<div class="accordion accordion-flush" id="<?php echo esc_attr( $accordion_id ); ?>">
			<?php
			foreach ( $faqs as $index => $faq ) {
				$question    = $faq['question'] ?? '';
				$answer      = $faq['answer'] ?? '';
				$collapse_id = wp_unique_id( 'faq-collapse-' );
				?>
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button
						class="accordion-button<?php echo 0 !== $index ? ' collapsed' : ''; ?>"
						type="button"
						aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>"
						aria-controls="<?php echo esc_attr( $collapse_id ); ?>"
					>
						<?php echo wp_kses( $question, $br_allowed ); ?>
					</button>
				</h2>
				<div id="<?php echo esc_attr( $collapse_id ); ?>" class="accordion-collapse<?php echo 0 === $index ? ' show' : ''; ?>">
					<div class="accordion-body"><?php echo nl2br( wp_kses( $answer, $br_allowed ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses()'d, then nl2br() adds only <br>. ?></div>
				</div>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
	if (
		!('IntersectionObserver' in window) ||
		window.matchMedia('(prefers-reduced-motion: reduce)').matches
	) {
		return;
	}

	var section = document.getElementById(<?php echo wp_json_encode( $section_id ); ?>);

	if (!section) {
		return;
	}

	var observer = new IntersectionObserver(
		function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				section.classList.add('is-in-view');
				observer.disconnect();
			});
		},
		{
			threshold: 0.2,
			rootMargin: '0px 0px -10% 0px',
		}
	);

	observer.observe(section);
});
</script>
