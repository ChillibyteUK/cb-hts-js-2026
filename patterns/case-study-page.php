<?php
/**
 * Title: Case Study Page
 * Slug: cb-hts-js-2026/case-study-page
 * Description: Project case study — hero, spec bar, facts sidebar beside requirement/solution/benefit, outcome stats, gallery and product used.
 * Categories: cb-hts-js-2026-pages
 * Keywords: case study, project, page, recipe
 * Post Types: project
 * Block Types: core/post-content
 * Inserter: true
 *
 * @package cb-hts-js-2026
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cb-hts-js-2026/cb-product-hero {"tag":"Case Study - Building Type","h1":"Case study headline","lede":"<p>Client descriptor.</p><p>One-sentence outcome.</p>","bullets":"<strong>Client:</strong> Client type\n<strong>Location:</strong> Region","ctaPrimaryText":"Discuss a similar project","ctaPrimaryUrl":"/contact/","ctaSecondaryText":"View the gallery","ctaSecondaryUrl":"#gallery","badgeNumber":"0","badgeSuffix":"m²","badgeLabel":"Headline Metric"} /-->

<!-- wp:cb-hts-js-2026/cb-spec-bar {"specItems":[{"label":"Structure Type","value":"Value","unit":"","id":"row-spec-1"},{"label":"Width","value":"0.00","unit":"m","id":"row-spec-2"},{"label":"Length","value":"0.00","unit":"m","id":"row-spec-3"},{"label":"Site height","value":"0.00","unit":"m","id":"row-spec-4"}]} /-->

<!-- wp:spacer {"height":"3rem"} -->
<div style="height:3rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:cb-hts-js-2026/cb-case-study-sidebar {"facts":[{"label":"Client","value":"Client name or type","link":"","linkText":"","linkTarget":false,"id":"row-fact-1"},{"label":"Sector","value":"Sector","link":"","linkText":"","linkTarget":false,"id":"row-fact-2"},{"label":"Location","value":"Region","link":"","linkText":"","linkTarget":false,"id":"row-fact-3"}],"ctaText":"Discuss a similar project","ctaUrl":"/contact/"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:cb-hts-js-2026/cb-heading {"heading":"The <span>requirement</span>","level":"H2","size":"H3"} /-->

<!-- wp:paragraph {"fontSize":"lede"} -->
<p class="has-lede-font-size">Lead sentence describing the client's requirement.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Supporting detail.</p>
<!-- /wp:paragraph -->

<!-- wp:cb-hts-js-2026/cb-heading {"heading":"The <span>solution</span>","level":"H2","size":"H3"} /-->

<!-- wp:paragraph {"fontSize":"lede"} -->
<p class="has-lede-font-size">Lead sentence describing the HTS solution.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-cb-ticked"} -->
<ul class="wp-block-list is-style-cb-ticked"><!-- wp:list-item -->
<li><strong>Key delivery point</strong> with detail</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Key delivery point</strong> with detail</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Key delivery point</strong> with detail</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph {"className":"is-style-cb-callout"} -->
<p class="is-style-cb-callout">Callout — a notable detail or constraint.</p>
<!-- /wp:paragraph -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img alt=""/></figure>
<!-- /wp:image -->

<!-- wp:cb-hts-js-2026/cb-heading {"heading":"The <span>benefit</span>","level":"H2","size":"H3"} /-->

<!-- wp:paragraph {"fontSize":"lede"} -->
<p class="has-lede-font-size">Lead sentence describing the result for the client.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:cb-hts-js-2026/cb-text-stats {"variant":"quote","eyebrow":"The outcome","headline":"One-sentence outcome statement.","stats":[{"value":"0<sup>m²</sup>","label":"Stat label","id":"row-outcome-1"},{"value":"0","label":"Stat label","id":"row-outcome-2"},{"value":"0","label":"Stat label","id":"row-outcome-3"}]} /-->

<!-- wp:cb-hts-js-2026/cb-client-projects-gallery {"layout":"feature","heading":"Project <span>gallery</span>"} /-->

<!-- wp:cb-hts-js-2026/cb-product-used /-->
