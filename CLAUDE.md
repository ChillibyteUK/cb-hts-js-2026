# cb-hts-js-2026

Read this in full before touching anything — several decisions here are
deliberate and not obvious from the code alone. If you're about to reach for
Sass, Bootstrap, jQuery, an icon font, or flexbox-for-everything, stop and
re-read the relevant section below first.

## What this is

A standalone WordPress theme skeleton — no parent theme, no framework
dependency. It exists to be checked out per client project, renamed, and
built on. It is **not** a live dependency: this repo is a one-time-checkout
base. Fixes made here are not expected to sync back into projects that have
already forked from it, and there is no submodule/subtree relationship to
maintain.

It replaces an earlier workflow built on Understrap (a Bootstrap 5 WordPress
starter theme used as a WP parent/child theme pair). That workflow is still
used for the rare WooCommerce project — this skeleton is deliberately for
everything else, which is the large majority of projects.

**This is a fork of `lc-skeleton2026`, kept deliberately separate rather than
merged in.** Both skeletons share the same CSS/JS/build system verbatim — the
only difference is how blocks and site-wide settings are built: `lc-skeleton2026`
uses ACF (ACF Blocks, ACF options page), this one uses native
block.json/edit.js/render.php blocks and a plain Settings API page, with no
ACF dependency at all. The split exists because ACF Blocks stopped reliably
saving field edits under WP 7.1's now-mandatory iframed block canvas — ACF's
own block-editor JS bridge only wires up field-change listeners on a block's
very first mount and doesn't re-arm when a block's canvas swaps from its
static preview into the editable form on selection, so typed edits can be
silently lost. Native blocks don't have this problem: field state flows
through React's own `setAttributes()`, which Gutenberg's iframe was built to
support correctly. Both skeletons are maintained in parallel on the chance
WP reverts or LTS's a pre-7.1 release — if that never happens, this one is
likely to become the only one going forward.

## The one rule that explains most of the file layout

**Bootstrap-style class names, zero Bootstrap.** `.container`, `.row`,
`.col-6`, `.btn`, `.navbar`, `.d-flex`, etc. all exist and are used exactly
like they'd look in a Bootstrap project — but every one of them is defined
in this theme's own CSS. There is no Bootstrap package, no Bootstrap Sass,
no Bootstrap JS, no Popper. The naming was kept on purpose for muscle-memory
continuity (for the theme author and any devs who've worked in Bootstrap
before) — **do not assume behavioural parity with actual Bootstrap.**
Anything not explicitly implemented here (most Bootstrap components) simply
doesn't exist, no matter how standard it looks.

## Architecture decisions and why

- **No Sass.** Native CSS nesting (browsers handle it natively at this
  project's browser baseline — see below) replaces the one thing Sass was
  doing that mattered. Don't add Sass back in "just for tokens" or "just for
  mixins" — there was a deliberate decision to not need a preprocessor at
  all here.
- **CSS Grid, not flexbox, for layout.** `.row`/`.col-*` are Grid-based (see
  "How the grid actually works" below) — this was chosen over flexbox
  because the person building this genuinely prefers Grid's mental model,
  and it sets up cleanly for `subgrid`. `.navbar` itself is flexbox — a
  single-row toolbar is a legitimate flex use case; Grid isn't a mandate for
  every layout, just the default for anything row/column/page-structure
  shaped.
- **Design tokens are CSS custom properties**, not Sass variables — see
  `src/css/tokens.css`. This is also what makes color/font-size options
  show up in the Gutenberg editor (`src/build/generate-theme-json.js` reads
  this file to produce `theme.json`).
- **Breakpoints are the one exception to "tokens live in tokens.css."**
  A CSS custom property can't be read inside an `@media` condition, so
  breakpoints live in `src/build/tokens.config.js` instead. `src/css/nav.css`
  hardcodes the `lg` breakpoint (992px) directly in a plain `@media` query
  for the same reason, in a comment noting it must stay in sync with that
  file. If you change a breakpoint, grep for the old pixel value across
  `src/css/*.css` — there is no single source of truth enforced by tooling,
  only by convention.
- **No Bootstrap JS, no jQuery.** `src/js/nav-toggle.js` is a ~20-line
  vanilla replacement for Bootstrap's Collapse component (mobile nav toggle).
  `src/js/dialog.js` wires up the native `<dialog>` element (`showModal()`/
  `close()`) as the modal solution — not a JS component library.
- **No icon font.** Icons are inline SVG (see `header.php`'s nav toggle
  button for the pattern). Don't add Font Awesome or similar back in.
- **Buttons and cards have zero framework opinion.** `.btn` in
  `src/css/forms.css` is a bare minimal base — the theme author designs
  buttons and cards per-project rather than using a framework's look, so
  don't build out an opinionated button/card system here without being
  asked.
- **Tables are real but opt-in.** `src/css/tables.css` exists and is
  genuinely styled, but is not imported by default in `src/css/theme.css` —
  uncomment the `@import` if a project needs one.
- **Deliberately absent, don't add back without being asked:** sidebars/
  widget areas, comments, tags, author archives, `archive.php`, `search.php`.
  All confirmed rare-to-never in real usage across ~40 projects/year. If a
  specific project needs one, add it there, not here.
- **Site-Wide Settings is core, not per-project.** `inc/options.php` — a plain Settings API page (no ACF), storing one serialized array option (`cb_hts_js_2026_site_settings`). Read values elsewhere with `cb_hts_js_2026_get_setting( $key )`. "Crucial to every theme" per the person building this. GA/GTM only fire for logged-out visitors (`inc/head-tags.php`) so the team's own traffic doesn't skew analytics — a real, previously-known gap in the old `lc-iology2025` theme (fires for everyone there), fixed here from the start rather than retrofitted. GTM's noscript fallback is on `wp_body_open`, not buried in the footer — that's where Google's own docs say it belongs. The SVG icon-upload tab the ACF version of this page had is deliberately not ported here — deferred to a future plugin, not rebuilt as part of dropping ACF. `get_icon()`/`get_icon_choices()` (`inc/utilities.php`) still work exactly as before; just drop an .svg into `img/icons/` by hand instead of uploading it through wp-admin. Fields on this project's settings page: `email`, `phone`, `utility_message`, `facebook_url`, `instagram_url`, `linkedin_url`, `footer_accreditation_ids` (see the "gallery" field type below), `ga_property`, `gtm_property`, `google_site_verification`, `bing_site_verification`, `client_logos` (see the "repeater" field type below).
- A `gallery` field type exists in `inc/options.php` alongside the plain text/email/url ones (`cb_hts_js_2026_render_gallery_field()`) — a hidden CSV-of-attachment-IDs input plus a thumbnail strip, driven by the core media modal in multi-select mode (`js/gallery-field.js`, enqueued only on the settings screen via `cb_hts_js_2026_settings_page_assets()`). Built for `footer_accreditation_ids` (the footer's accreditation badge row) rather than a full ACF-style repeater, since it's a fixed list of images, not repeating structured content. Read it with `cb_hts_js_2026_get_footer_accreditation_ids()`, which returns an array of attachment IDs.
- A `repeater` field type also exists in `inc/options.php` for settings that
  genuinely are repeating structured rows (unlike `gallery`'s flat image
  list) — e.g. `client_logos` (name + logo pairs, driving `CB Selected
  Clients`). WordPress gives Gutenberg a first-class repeater experience via
  `InnerBlocks`/array attributes, but nothing equivalent exists for plain
  Settings API pages outside ACF PRO — this is the from-scratch replacement,
  built once so it's free infrastructure on every future project checkout
  rather than a per-project cost (same reasoning as `gallery`, one level up
  in complexity). A field declares `'type' => 'repeater'` plus a
  `'sub_fields'` array (`[ key => [ label, type: 'text'|'image' ] ]`); rows
  post as a genuinely nested PHP array (`option[key][row_index][sub_key]`)
  with **no JSON encoding at all** — the Settings API already parses nested
  `$_POST` array structures natively, so this stays inside the existing
  "one serialized array option" model for free. Row order following DOM
  order rather than needing renumbered indexes is deliberate: `js/repeater-
  field.js` reorders rows with a plain DOM move (no index bookkeeping),
  since PHP preserves array insertion order (= submission order = DOM
  order) regardless of what the actual (non-sequential, timestamp-based) row
  keys are. Read a repeater setting generically with
  `cb_hts_js_2026_get_repeater_setting( $key )`, or add a thin typed wrapper
  per use (see `cb_hts_js_2026_get_client_logos()`) the way
  `footer_accreditation_ids` already has one. Row markup (including the
  empty `<template>` row `js/repeater-field.js` clones for "Add row") is
  rendered once, server-side, by `cb_hts_js_2026_render_repeater_row()` —
  the JS only wires interactions, it doesn't know about specific sub-field
  names, so a new repeater with different sub-fields needs zero JS changes.
  This is the *wp-admin* half of the "generic repeater" work — see the
  separate, React-based `blocks/_shared/RepeaterField.js` under "Repeater
  pattern" further down for the *block-editor* half. Same sub-field
  vocabulary (`text`/`textarea`/`image`) by design, deliberately two
  separate implementations, since one runs in a plain wp-admin form and the
  other inside Gutenberg's React tree — don't try to unify them.
  **Reused/renamed class names between the two are a real pitfall**: the
  wp-admin repeater's classes are prefixed `cb-hts-js-2026-settings-repeater`
  rather than reusing the block-editor component's
  `cb-hts-js-2026-repeater-field` prefix, specifically because the two load
  in completely different CSS contexts (plain wp-admin page vs. the
  Gutenberg iframe's `add_editor_style()`) and a shared name would have
  looked like a real connection that wasn't there.
- **Native blocks, not ACF.** Each block is a directory under `blocks/{slug}/`:
  `block.json` (name/title/attributes/supports), `src/index.js` +
  `src/edit.js` (the editing UI — React, using WordPress's own field
  components), and `render.php` (front-end template, reading `$attributes`
  directly — no `get_field()`, there's no ACF to call). `inc/blocks.php` globs
  `blocks/*/block.json` and registers every one automatically — no per-block
  PHP registration code, unlike the marker-comment insertion the ACF version
  used. Fields never render as a click-to-reveal preview or get shunted into
  the sidebar — they're just always-live inputs in the canvas, because that's
  how native blocks work, not something forced on top. Blocks register into
  a category named after the theme itself (text domain as slug, theme Name
  as title — `cb_hts_js_2026_register_theme_block_category()` in
  `inc/blocks.php`), not a generic `"layout"` or `"theme"` bucket. Don't use
  the literal slug `"theme"` for this — WordPress core already registers a
  category with that exact slug for legacy widget blocks, and this project's
  `cb_hts_js_2026_get_disallowed_gutenberg_blocks()` disallows that whole
  category as noise, which silently hides any block registered under a
  colliding `"theme"` slug too (this happened once already — see the
  migration log below).

## Browser support baseline

Modern evergreen only — see `.browserslistrc` (last 2 versions of Chrome/
Firefox/Safari/Edge, no IE11). This is why CSS Grid, `subgrid` (used as
progressive enhancement via `@supports`, not depended on), `clamp()`,
native `<dialog>`, `:has()`, and CSS nesting are all used without fallback
layers. Don't add polyfills or fallback CSS for older browsers unless
explicitly asked — it would be working against a deliberate decision.

## How the grid actually works

`.row { display: grid; grid-template-columns: repeat(var(--grid-columns), 1fr); }`
and `.col-N { grid-column: span N; }` — `--grid-columns` defaults to 12
(`src/css/tokens.css`). **The span number is only meaningful relative to
whatever `grid-template-columns` its own `.row` ancestor has.** You cannot
mix "some children spanning against a 12-col row" with "other children
spanning against a 5-col row" inside the *same* `.row` — a `.col-*` class is
not portable across different column-count contexts.

If a project needs a genuinely different column count (the theme author's
example: five equal columns), don't repurpose `.col-*` classes for it.
`.grid` (`src/css/layout.css`) is the built solution: `display: grid;
grid-template-columns: repeat(auto-fit, minmax(var(--grid-min), 1fr));`
— no column count is declared at all, the browser fits as many as the
minimum item width (`--grid-min`, default `16rem` in `tokens.css`)
allows, and reflows automatically as the viewport changes. Tune it per
instance with an inline `style="--grid-min: 10rem"` rather than adding
a new class per layout.

This is deliberately *not* a replacement for `.row`/`.col-*` — use `.row`
when you need deliberate, exact spans (page structure); use `.grid`
when items just need to be "roughly N up, however many fit" (card grids,
feature lists). A row-modifier approach (overriding `--grid-columns` for one
specific `.row` with its own `.col-*-of-5`-style classes) was considered and
rejected in favour of `.grid` for this use case — more bookkeeping for
less benefit when the real need is "N similar items," not exact spans.

Nested `.row`s use `subgrid` for their columns where the browser supports it
(`@supports (grid-template-columns: subgrid)` in `src/css/layout.css`),
falling back to their own independent 12-column grid otherwise.

## File layout

```
style.css              Theme header — no `Template:` line, this is standalone
functions.php           Requires inc/*.php, nothing else
inc/
  setup.php             add_theme_support, register_nav_menus
  enqueue.php           Enqueues css/theme.min.css + js/theme.min.js, filemtime-versioned
  class-nav-walker.php  Lightweight Walker_Nav_Menu — nav-link/dropdown-menu classes, no JS
  blocks.php            Globs blocks/*/block.json and register_block_type()s each one — no per-block code
  options.php           Site-Wide Settings page (plain Settings API, one array option) + cb_hts_js_2026_get_setting()
  head-tags.php          Font preload (fonts/*.woff2 glob) + GA/GTM (logged-out only) + Google/Bing verification, reading from the options page
  block-usage.php        [block_usage_table] shortcode — QA utility, lists every block against the published pages/posts using it
  utilities.php          Reusable, project-agnostic functions (parse_phone, pluralise, estimate_reading_time_in_minutes, get_icon/get_icon_choices) — safe to lift verbatim into any project on this skeleton. Project-specific helpers go in inc/helpers.php instead, created only when needed, not scaffolded here.
header.php / footer.php / index.php / page.php / single.php / 404.php
                        Deliberately minimal — most real page layouts are built from blocks, not these
blocks/                 One directory per block (add_block.sh scaffolds here)
  {slug}/
    block.json          Name/title/attributes/supports — "editorScript": "file:./build/index.js", "render": "file:./render.php"
    src/index.js        registerBlockType(), imports edit.js + block.json
    src/edit.js          The editing UI — React, WordPress's own field components (TextControl, RichText, MediaUpload, ...)
    render.php           Front-end template — reads $attributes directly, no get_field()
    build/               GENERATED by wp-scripts, see webpack.config.js — do not hand-edit or commit assumptions about its contents surviving a clean checkout
fonts/                  Drop .woff2 files here — preloaded automatically, no registration step
src/
  css/                  Theme-wide CSS. theme.css is the @import entry point.
    tokens.css          Design tokens as CSS custom properties (colors, spacing, type)
    base.css            Reboot-equivalent element reset
    layout.css           .container / .row / subgrid
    nav.css             .navbar, mobile toggle, dropdown submenus
    forms.css           Minimal form base + .btn
    tables.css          Opt-in, not imported by default
    utilities.css        GENERATED — do not hand-edit, see generate-utilities.js
    blocks.css           GENERATED — concatenation of src/blocks/*.css
  blocks/               Block-specific CSS, separate from theme-wide src/css/.
                        {block-slug}.css — add_block.sh does NOT create this file;
                        drop one in here and it's picked up automatically on next
                        build (glob, alphabetical order, no registration step).
  js/
    theme.js            Entry point, imports the two below
    nav-toggle.js        Mobile nav collapse — vanilla JS replacement for Bootstrap Collapse
    dialog.js            Native <dialog> wiring — vanilla JS replacement for Bootstrap Modal
  build/
    tokens.config.js     Breakpoints + utility/grid definitions — source of truth for generate-utilities.js
    generate-utilities.js  Generates src/css/utilities.css and src/css/blocks.css
    generate-theme-json.js Generates theme.json from src/css/tokens.css
    postcss.config.js    postcss-import + postcss-nesting + autoprefixer
    rollup.config.js / babel.config.js / terser.config.json / banner.js
                        JS bundling — no nodeResolve/commonjs, there are no npm JS deps to bundle
    browser-sync.config.js
css/ , js/              Compiled output — committed to git (not gitignored), same convention as
                        the Understrap-based themes this replaced
add_block.sh            Prompts for a block name, then loops prompting for each field's name and
                        type (text/textarea/richtext/image/url/link/number/select/checkbox). For
                        each field it also asks for optional help text (rendered via the `help`
                        prop, or a matching paragraph for RichText/MediaUpload fields) and a column
                        width (100/50/33/25) — consecutive non-100% fields share a flex row sized
                        by flex-grow ratio, not a fixed percentage (avoids overflow from fixed
                        percentages plus gaps when e.g. three 33% fields sit in one row). `link`
                        fields can optionally get an "open in new tab" toggle. `textarea` fields can
                        render as a paragraph (default, wpautop), a `<ul><li>` list, or newline-to-
                        `<br>`, picked per field. Generates block.json's attributes, the matching
                        src/edit.js control, and a render.php $attributes stub for each field — the
                        render.php it produces is a skeleton to hand-finish, not a finished
                        template; add_block.sh's job is registering the block and its editor fields,
                        not writing final markup. Blocks register into the theme's own category
                        (see above), not `"layout"`. Post-processes the generated render.php to
                        collapse any `?>` immediately followed by `<?php` with nothing meaningful
                        between them into one continuous PHP region — concatenating one per-field
                        `<?php if () { ?> ... <?php } ?>` fragment used to produce exactly that
                        violation at every field boundary (see PHP style rules below). Not yet
                        supported by the generator: **repeater**, gallery, relationship, post_object,
                        file — add those by hand in src/edit.js. For repeaters specifically, see
                        "Repeater pattern" below rather than hand-rolling a custom array-attribute
                        UI. Does not create a CSS file — see src/blocks/ above. Reminds you to run
                        `npm run blocks:build` afterward — the block won't work until build/index.js
                        exists. `npm run watch`/`watch-bs` now include `blocks:start` in their
                        parallel task list — block JS changes didn't used to be watched at all (only
                        theme-wide CSS/JS was), which looked like "the block silently disappeared"
                        after an edit. If block edits stop showing up, check that `blocks:start` (or
                        a manual `npm run blocks:build`) actually ran after the last change — a
                        stale/unbuilt `build/index.js` next to a valid `block.json` produces "Your
                        site doesn't support this block" in the editor even though the block is
                        registered correctly server-side.
rm_block.sh             Removes a block: the whole blocks/{slug} directory, and
                        src/blocks/{slug}.css if present. No registration to undo.
setup.sh                One-time bootstrap for a NEW project checked out from this skeleton —
                        prompts for a theme name + slug, renames every old-name/old-slug/old-prefix
                        reference, resets git to a fresh single commit. Refuses to run twice
                        (idempotency guard) or on a dirty tree. Does NOT create a GitHub repo —
                        that's a deliberate manual step (gh repo create), not automated. See
                        README.md. **This project's own copy of setup.sh already ran once** (that's
                        how this checkout became `cb-hts-js-2026` from the `lc-js-skeleton2026`
                        skeleton) — because setup.sh's own rename loop runs over every tracked file
                        including itself, its `old_slug`/`old_prefix*` variables now read
                        `cb-hts-js-2026`/`cb_hts_js_2026` instead of the skeleton's originals. **Do
                        not run it again in this checkout** — it would try to rename this already-
                        renamed project a second time. Its idempotency guard checks for its own
                        `old_slug` in style.css, which post-self-rewrite now matches this project's
                        *current* slug, so the guard would not reliably stop it. If this project
                        ever needs to be re-templated from scratch, re-clone the skeleton fresh
                        rather than reusing this copy of the script.
theme.json              GENERATED by generate-theme-json.js — do not hand-edit
```

## Build commands

```
npm install
npm run watch        # rebuild theme-wide CSS/JS on save
npm run watch-bs     # same + browser-sync live reload, proxies localhost/
npm run blocks:build # one-off compile of every blocks/*/src/index.js
npm run blocks:start # watch mode for block JS, wp-scripts' own watcher
npm run dist         # one-off full build: css + js + blocks:build
npm run generate-theme-json
./add_block.sh
```

`npm run css` = `generate-utilities.js` (writes `utilities.css` + `blocks.css`)
→ PostCSS (`postcss-import`, `postcss-nesting`, `autoprefixer`) → minify.
`npm run js` = rollup (bundles `src/js/theme.js`) → terser. Both are the
theme-wide build, entirely separate from blocks — they don't touch or know
about `blocks/*/src/`.

`npm run blocks:build`/`blocks:start` run `@wordpress/scripts` (`wp-scripts`)
against `webpack.config.js`, which globs `blocks/*/src/index.js` and compiles
each one to `blocks/{slug}/build/index.js` — co-located with that block's own
`block.json`, not wp-scripts' single top-level `build/` default.
**`webpack.config.js` explicitly disables webpack's `output.clean`** — the
default config cleans `output.path` before every build, and since
`output.path` here is the shared `blocks/` directory (so `[name]/build/...`
resolves per block), an enabled clean would wipe every block's
`block.json`/`render.php`/`src/` alongside the compiled output. Don't remove
that `clean: false` without replacing it with something that can't do that.

## Working conventions carried over from the previous (Understrap) themes

- Compiled `css/`/`js/` output is committed to git, not gitignored. The same
  applies to each block's `build/` output — commit it, don't gitignore it,
  same reasoning.
- No ACF anywhere in this theme — see `lc-skeleton2026` (the sibling
  ACF-based skeleton this one forked from) if a project specifically needs
  ACF instead of native blocks.

## HTS Industries migration — project-specific status

Everything above this heading is generic skeleton documentation, mostly
inherited from `lc-js-skeleton2026`. Everything below is specific to what
this checkout (`cb-hts-js-2026`) is actually for: **rebuilding the HTS
Industries site** (currently live on the old Understrap/ACF-based
`cb-hts2026` theme) into a from-scratch ACF-free theme, block by block,
so it can be built on going forward without depending on ACF Blocks (see
"What this is" at the top for why).

### The three environments involved

- **`/var/www/hts/wp-content/themes/cb-hts2026/`** — the SOURCE theme. Old
  Understrap/Bootstrap-Sass + ACF Blocks. Read-only reference — never edit
  this. Every block, template, and style value being ported comes from here:
  `blocks/*.php` (ACF PHP block templates), `acf-json/*.json` (field group
  definitions — the authoritative list of each block's fields, types, and
  `wrapper.width` values), `src/sass/theme/**/*.scss` (styling ground truth),
  `inc/*.php` (helper functions like `cb_list()`, `parse_phone()`,
  `cb_marquee_script()`).
- **`http://hts.local/`** — the SOURCE site, running the theme above with
  real content. Used as the ground-truth for every visual value via
  `getComputedStyle()`/`getBoundingClientRect()` in a real browser — see
  "Identical, not close" below. Also has the real ACF field data (menus,
  settings, accreditation images) that this migration reads structurally but
  does **not** copy content from (see below).
- **`http://wp72test.local/`** (filesystem: `/var/www/wp72test/`) — the
  DESTINATION/working site, running this theme (`cb-hts-js-2026`), where all
  new work actually happens and gets tested. Has its own (mostly placeholder)
  content, menus, and Site-Wide Settings — not a clone of the source site's
  database. When testing a block, content is created ad hoc via `wp eval`/
  `wp post create` and cleaned up afterward, not pulled from hts.local.

### Content vs. structure — what actually gets migrated

Per explicit standing instruction: **ignore the content, migrate the
structure.** The goal is a theme from which the real hts.local site can be
*rebuilt* by hand afterward — pages, real copy, and real media are the
client's/developer's job once the blocks/templates exist, not something to
scrape and copy across during this migration. Menus, settings values, and
test content added to wp72test during block development are throwaway
scaffolding for verifying a block works, not the deliverable.

### "Identical, not close" — the methodology, non-negotiable

This was established after repeated correction, stated with real force: never
estimate, guess, or approximate a visual value. For every block/template
being ported:

1. Read the source PHP template and ACF field-group JSON **in full** first —
   this is ground truth for structure, field names/types, and conditional
   logic (e.g. `wrapper.width` in the ACF JSON tells you which fields should
   sit side-by-side and at what ratio).
2. Read the source SCSS file(s) **in full** — ground truth for every CSS
   rule and value.
3. **Verify every rendered value against the live hts.local site** via
   `getComputedStyle()` / `getBoundingClientRect()` in a real browser tab —
   colors, font sizes, spacing, line-heights, letter-spacing, breakpoints,
   everything. Do not trust the SCSS file alone if it's ambiguous (e.g.
   custom-property fallbacks, computed `clamp()` results) — measure the
   actual computed pixel value.
4. Only then write the destination code, mapping source tokens onto this
   project's own token *names* (see "Token name mapping" below) without
   changing the *values*.
5. Verify the result by rendering it in the destination site's browser and
   comparing side-by-side against the source, not just by eyeballing the
   code.

Deviating from an already-correct source value (e.g. "improving" a hardcoded
`2026` copyright year into a dynamic `gmdate('Y')`) is also a violation of
this rule in the other direction — port what's there, don't improve on it
unless asked. Two exceptions found and *deliberately* not carried forward
because they were latent bugs in the source, not real requirements: a
dangling `--col-accent-400`/`--shadow-active` reference in the source that
was never actually defined there either; and an empty, functionally-unused
`<div id="footer-top">` in the source footer with no CSS/JS/anchor
referencing it anywhere.

### Token name mapping (source SCSS → this project's tokens.css)

Both use CSS custom properties, but under different naming conventions —
values must match exactly, names follow *this* project's existing
convention rather than the source's:

| Source (`_tokens.scss`) | This project (`tokens.css`) |
|---|---|
| `--fw-medium: 500` | `--fw-500` |
| `--fw-semibold: 600` | `--fw-600` |
| `--fw-bold: 700` | `--fw-700` |
| `--fw-heavy: 800` | `--fw-800` |
| `--ff-base` | `--font-family-base` |

Every other token this migration has needed so far (`--fs-*`, `--lh-*`,
`--ls-*`, `--col-*`, `--container-max-width`, `--space-*`) was already
authored into `tokens.css` with the *destination* name matching the *source*
value from earlier work in this same session — check there before assuming
a new token needs adding. `--container-max-width: 1360px` is confirmed
correct against the live site. `--container-padding` was *not* — it was set
to `var(--space-3)` (16px) but the source's actual `.container` padding is
12px each side (`0.75rem`, half of Bootstrap's `$grid-gutter-width: 1.5rem`
— see `_child_theme_variables.scss`/`_variables.scss` in the source theme),
only caught because it changed how a headline wrapped on the `CB Intro`
block at a specific viewport width, not from a direct side-by-side check.
**Lesson: a value "confirmed correct" at one viewport isn't confirmed at
all** — `.container`'s source is a Bootstrap grid with per-breakpoint
stepped `max-width`s (540/750/970/1170/1360px), not fluid like this
project's reimplementation, so padding and max-width need checking
independently of each other and at a wide-enough viewport that the source's
stepped container has actually reached its final breakpoint (roughly
≥1400px) — checking at a narrower width will show the source container
looking artificially narrow for reasons that have nothing to do with
padding.

### Standing PHP style rules (repeatedly enforced — do not regress)

1. **Braces only.** Never `if (...) : ... endif;` / `endforeach;` / colon
   syntax, even mixing HTML and PHP — always
   `<?php if ( $x ) { ?>...<?php } ?>`.
2. **Never immediately close and reopen a PHP tag** with nothing meaningful
   between — no bare `?>` followed by `<?php` separated only by whitespace.
   Concatenating independently-generated `<?php if () { ?> ... <?php } ?>`
   fragments (as `add_block.sh` used to, and as the *source* theme's own
   `cb-home-hero.php` does around its badge-number/suffix split) produces
   exactly this violation — it must be fixed even when the source itself
   does it, since the source isn't held to this project's style rules.
   `add_block.sh` now auto-collapses this in its generated render.php (see
   the file-layout entry above); anything hand-written still needs checking
   by eye — grep for `?>\s*<?php` across a new file before considering it
   done.

These were violated multiple times early in this migration (header.php,
options.php, add_block.sh's generated templates) before being fixed
everywhere and turned into a standing rule enforced on every file touched
since, not just the one originally flagged.

### What's been migrated so far

- **Header/nav** (`header.php`, `inc/class-cb-hts-js-2026-nav-walker.php`,
  `src/css/header.css`/`nav.css`) — utility bar (live-build pill, phone/
  email from settings, external "HTS-Tentiq Global" link), primary nav via
  `wp_nav_menu()` with a custom walker (dropdown submenus, no Bootstrap JS),
  nav CTA buttons. Fully verified against the source pixel-for-pixel.
- **Footer** (`footer.php`, `src/css/footer.css`) — logo/tagline/social
  icons, two `wp_nav_menu()` columns (Products/Applications), contact block,
  accreditation badges (see `footer_accreditation_ids` gallery field above),
  bottom bar. The `g-4 g-lg-5` Bootstrap gutter classes in the source's
  footer-top row don't exist in this project's CSS-Grid-based `.row` system
  (no Bootstrap gutter utilities at all) — replaced with a scoped
  `.footer-top` gap override using this project's own `--space-4`/`--space-5`
  tokens, which happen to equal Bootstrap's `g-4`/`g-lg-5` values (24px/48px)
  exactly.
- **CPTs and taxonomies** (`inc/posttypes.php`, `inc/taxonomies.php`) —
  `application`/`product`/`project` post types and `application_cat`/
  `project_cat` taxonomies, ported with exact args from the source. Also:
  `cb_hts_js_2026_use_page_template_for_cpts()` (serves `page.php` instead of
  `single.php` for CPT singular views — block-built content doesn't want
  blog-post chrome) and `cb_hts_js_2026_nav_highlight_cpt_parent()` (adds
  `current-menu-parent`/`current_page_parent` nav classes when viewing a CPT
  whose real parent is an ordinary Page, since `has_archive: false` CPTs have
  no archive of their own to be "under").
- **Design tokens** (`src/css/tokens.css`) — full HTS palette, type scale,
  line-heights, letter-spacing, all measured against the live site (see
  "Token name mapping" above for the naming convention).
- **`CB Home Hero` block** (`blocks/cb-home-hero/`) — 17 fields ported from
  `group_cb_home_hero.json`: meta strip (list-style textarea), two-line H1
  with per-line highlight spans (50/50 field pairs), lede (richtext),
  bullets (list-style textarea), two CTAs (link fields with new-tab
  toggles, 50/50), image, badge number/suffix/label (33/33/33 trio). Same
  scroll-parallax `<script>` as the source, conditional on an image being
  set. `src/blocks/cb-home-hero.css` ported from `_cb_home_hero.scss`
  verbatim (native CSS nesting, matching this project's existing convention
  — confirmed via `nav.css`, not flattened).
- **`CB Marquee Stats` block** (`blocks/cb-marquee-stats/`) — see "Repeater
  pattern" below, the reference implementation for any repeater-shaped
  block. GSAP-driven horizontal ticker.
- **`CB Intro` block** (`blocks/cb-intro/`) — 5 fields: eyebrow, headline
  (raw textarea, authors type `<span>`/`<br>` by hand, matching the source's
  `wp_kses()` allow-list exactly), body (`RichText` with `multiline="p"` as
  the native equivalent of ACF's "basic" toolbar WYSIWYG), signature and
  highlights (50/50, per `wrapper.width` in the ACF JSON). Added two new
  *global* typography utilities while migrating this — `.eyebrow` (with
  `--plain`/`--light` modifiers) and `.h1`–`.h6` (distinct from the bare
  `h1`–`h6` element selectors in `base.css`; applied alongside a heading's
  own class, e.g. `<h2 class="intro-headline h2">`) — both in
  `src/css/typography.css`, since they're global source typography classes
  this was the first migrated block to actually need, not intro-specific.
  **Nesting caveat hit while writing both**: this project's PostCSS nesting
  does not resolve Sass-style `&-suffix`/`&--suffix` concatenation (e.g.
  `.eyebrow { &--plain { ... } }` compiled to the broken selector
  `--plain.eyebrow`, and `.intro { &-inner { ... } }` silently collapsed
  every nested rule back to plain `.intro`) — only combinator-based nesting
  (`&:hover`, `&::after`, `& > *`, bare descendant selectors like `p {}`
  nested inside a parent) actually works. Nobody had used the `&-suffix`
  form anywhere else in the project before, so this wasn't a proven
  convention being followed, it was an untested assumption — write new
  nested block/typography CSS with explicit full class names instead of
  Sass-style suffix concatenation, and if genuinely unsure, check the
  compiled `css/*.min.css` output before trusting a nested rule compiled
  correctly.
- **`CB Selected Clients` block** (`blocks/cb-selected-clients/`) — a GSAP
  marquee of client logos with **zero fields of its own** (the source ACF
  group is a `message`-only placeholder); every logo comes from the new
  `client_logos` repeater on Site-Wide Settings (see "Generic repeater
  pattern for Site-Wide Settings" below). `edit.js` just shows a static
  canvas note pointing at Site-Wide Settings → Clients — there's genuinely
  nothing per-instance to edit. Shares `blocks/_shared/marquee-view.js` with
  `CB Marquee Stats` (same `[data-marquee]`/`[data-marquee-track]`
  mechanism) — `cb_hts_js_2026_enqueue_marquee_view_script()` in
  `inc/blocks.php` now gates on either block being present via `has_block()`,
  not just `cb-marquee-stats`.
- **`CB Applications Grid` block** (`blocks/cb-applications-grid/`) — 3
  header fields (eyebrow, headline, lede — same field patterns as `CB Intro`)
  plus a mosaic grid of cards **pulled live from the `application` CPT** via
  `WP_Query` (`orderby => 'menu_order date'`), not from block attributes at
  all — closer in spirit to `CB Selected Clients` (real content lives
  outside the block) than to a fields-only block. A card is only a link
  (`<a>`, hover arrow, `.app-card--linked`) when its post has body content;
  otherwise it renders as a non-interactive `<div>` tile — lets a CPT entry
  exist as a grid tile before its own page copy is written. Added the full
  `.prose-*` typography family (`lede`/`lg`/`md`/`base`/`sm`/`xs`) to
  `src/css/typography.css` while porting this — only `.prose-md` is used
  here, but it's a coherent global set (same reasoning as porting all of
  `.h1`–`.h6` when only `.h2` was needed for `CB Intro`). The header row's
  `.col-lg-5`/`.col-lg-7` split needed no new work — this project's
  `generate-utilities.js` already emits every `.col-{bp}-{n}` combination.
  Its `g-4 g-lg-5` Bootstrap gutter classes got the same treatment as
  `.footer-top`: dropped from the markup, replaced with a scoped
  `--space-4`/`--space-5` gap override on `.apps-header`.
- **`CB Configurator` block** (`blocks/cb-configurator/`) — 6 fields: tag,
  headline, intro (richtext), features (list-style textarea, `<strong>`/
  `<em>`/`<br>` allowed — matches the source's `cb_list()` helper's own
  allow-list, ported inline rather than as a shared PHP helper since it's a
  three-line loop already duplicated once for `CB Home Hero`'s `bullets`),
  CTA (link + new-tab toggle), image. First block needing Bootstrap's
  `w-100`/`w-md-auto` sizing utilities — added a `width` entry to
  `src/build/tokens.config.js`'s `utilities` map (`25/50/75/100/auto`,
  responsive by default) rather than a block-scoped override, matching every
  other Bootstrap-style utility this project already generates. Deliberately
  responsive (unlike stock Bootstrap, where sizing utilities aren't) because
  the *source* enables `"width": { responsive: true }` via its own
  `$utilities` map-merge in `_child_theme_variables.scss` — a real, if easy
  to miss, customisation over Bootstrap defaults, not an assumption.
- **`CB Product / Project Hero` block** (`blocks/cb-product-hero/`) — split
  hero for Product/Project single pages (as opposed to `CB Home Hero`, the
  homepage-only version). In the source this is one shared
  `template-parts/hero-split.php`, consumed by both the `acf/cb-product-hero`
  block *and* directly by `single-project.php` — the second consumer doesn't
  need porting here, since this theme already routes every CPT singular view
  through `page.php` (`cb_hts_js_2026_use_page_template_for_cpts()`), so
  "used directly by a project's single template" and "used as a block on a
  Project's block-built page content" collapse into the same thing: this one
  block. Reuses most of `CB Home Hero`'s CSS classes verbatim
  (`.hero`/`.hero-split`/`.hero-content`/`.hero-lede`/`.hero-bullets`/
  `.hero-actions`/`.hero-visual`/`.hero-img-wrap`/`.hero-badge*`) — only adds
  what's genuinely different in `src/blocks/cb-product-hero.css`: a bare
  (unclassed) `<span>` H1 accent instead of Home Hero's own generated
  `.accent`/`.underline` spans (this block's `h1` is one raw-HTML field
  authors type `<span>` into by hand, not Home Hero's four-field split-line
  system), the `.hero-tag` pill (a hardcoded `11px`/`.16em` in the source,
  deliberately *not* `--fs-micro`/`--ls-caps` even though those equal the
  same values elsewhere — kept as literals to match the source file
  verbatim), and a larger first-`<p>` inside a multi-paragraph `.hero-lede`.
  **Two judgment calls made while porting, worth knowing about:**
  - The source's `hero-split.php` also accepts a `subtitle`/`sub` parameter
    (rendered as `.hero-sub`), but **no corresponding field exists** in
    `group_cb_product_hero.json` — `get_field('sub')` in the source always
    returns empty through the normal editing UI. Confirmed this is genuinely
    unreachable, not just unlikely: measuring `.hero-sub` on a real live
    product page (`/products/industrial-canopies/`) showed a font-size that
    didn't match its own CSS rule at first, traced to the content author
    having hand-typed `<p class="hero-sub">` *inside* the `lede` WYSIWYG
    field (since there's no real subtitle field to use), which then also
    matched a *different*, higher-precedence rule
    (`.hero-lede p:first-child`) meant for something else entirely. Given
    the field group is the ground truth for what's actually editable, this
    block does **not** add a real `subtitle` attribute — that would be
    adding capability the source never exposed, not porting what's there.
    `.hero-sub`'s CSS is still ported (in case a future block or template
    needs it), just not wired to anything here.
  - `.hero-sub`'s own CSS rule lives in the source's `_case_study.scss`
    ("also available to the product hero block"), not in
    `_cb_product_hero.scss` — ported into `cb-product-hero.css` anyway
    rather than pulling in the rest of that file's breadcrumb-bar styles,
    which nothing in this project has needed yet.
- **`CB Specs` block** (`blocks/cb-specs/`) — sticky header column
  (eyebrow/headline/intro) beside a spec table, with a giant "SPEC"
  watermark that fades/settles in via `IntersectionObserver` on first
  scroll into view (`.is-in-view`, same one-observer-per-instance pattern as
  `CB Marquee Stats`'/`CB Selected Clients`' marquee script, but inline here
  rather than shared since nothing else needs it yet). `rows` is the second
  real use of the block-editor `RepeaterField` component (label text + value
  textarea) — no changes needed to the shared component itself, confirming
  it's genuinely reusable and not accidentally `CB Marquee Stats`-specific.
  Two source discoveries, both **not** carried forward as genuine bugs/dead
  code (same standing exception as the dangling `--col-accent-400` reference
  and the empty `#footer-top` div documented earlier in this file):
  - The source computes a `$bg = 'has-' . $block['backgroundColor'] . ...'`
    class from ACF Blocks' background-color support, but never actually
    applies `$bg` anywhere in the markup that follows — genuinely dead code,
    the background-color picker in the source's block inspector visibly
    does nothing. No `backgroundColor` attribute or color support was added
    to the native block.
  - The source's row markup is `class="row g-5 g-xl-6"`, but Bootstrap's
    `$spacers` map only defines steps 0–5 (`g-5` = `3rem`) and nothing in
    the source's own `_child_theme_variables.scss` extends it — `g-xl-6`
    compiles to no CSS rule at all and is a pure no-op in the live source.
    Only the real `g-5` got ported, as a `.specs .row { gap: var(--space-5)
    }` scoped override (same pattern as `.footer-top`/`.apps-header`/
    `.config-inner`), not a fabricated `--space-6` token to make `g-xl-6`
    "work" — that would be fixing a bug the source never actually had
    fixed, not porting what's there.
- **`CB Downloads` block** (`blocks/cb-downloads/`) — grid of downloadable
  PDFs (data sheets, certifications). `items` is the third real use of the
  block-editor `RepeaterField` component and the first to need a **`file`**
  sub-field type, added to `blocks/_shared/RepeaterField.js` alongside the
  existing `text`/`textarea`/`image` types (`MediaUpload` restricted via a
  new `mimeTypes` field-config option, showing the selected filename instead
  of an image preview). File type and size are **not** stored on the
  block — the source reads them live from the attachment
  (`pathinfo()`/`size_format()`) rather than trusting hand-typed values, and
  this block does the same (`get_attached_file()` + `filesize()` +
  `size_format()` at render time), so they can never drift out of sync with
  the actual uploaded file. Same for the cover image: `wp_get_attachment_image(
  $id, 'thumbnail', ... )` on a PDF returns a real page-one preview if the
  server's Imagick build supports PDF rendering (confirmed working on both
  hts.local and wp72test — a plain WP core capability, nothing this block
  needs to implement), falling back to a plain extension badge otherwise —
  ported verbatim, no new fallback logic needed.
  Unlike `CB Specs`' dead `$bg` computation, this block's Gutenberg
  background/text color support is **genuinely applied** in the source
  (`class="downloads <?= $bg . ' ' . $fg ?>"`) — so this is a real feature
  port, done the native way: `"supports": { "color": { "background": true,
  "text": true } }` in `block.json`, with `get_block_wrapper_attributes()`
  handling the `has-*-background-color`/`has-*-color` classes automatically
  from `theme.json`'s palette — no manual `$bg`/`$fg` string-building
  needed the way the source's ACF-Blocks-era code required.
  **While fixing an unrelated repeater UI request** (top-aligning fields
  instead of bottom-aligning — `.cb-hts-js-2026-repeater-field__row`'s
  `align-items` in `src/css/editor.css`, `flex-end` → `flex-start`), this
  became the first block whose repeater rows mix genuinely different-height
  controls (a two-line file-name/button image-style field next to plain
  text inputs), which is exactly the case that made bottom-alignment look
  wrong in the first place.
- **`CB Why Split` block** (`blocks/cb-why-split/`) — same sticky-column +
  scroll-triggered "WHY" watermark shape as `CB Specs` (`is-in-view` via
  `IntersectionObserver`, inline script, same reasoning for not sharing it —
  nothing else needs it yet), with a 2-column stats grid and a numbered
  reasons list instead of a table. Two repeaters (`stats`, `reasons`) — no
  new `RepeaterField` sub-field types needed, both are `text`/`textarea`
  combinations already covered.
  Two more instances of already-documented latent-source-bug handling,
  worth cross-referencing rather than re-explaining in full:
  - Source markup is `class="row g-5 g-xl-6"`, the exact same broken
    `g-xl-6` no-op already documented under `CB Specs` — same fix
    (`.why-split .row { gap: var(--space-5) }`).
  - `.why-split-stats` is source's `.row.row-cols-2.g-4` — Bootstrap's
    `row-cols-N` mechanism (each `.col` child auto-sized to `100% / N`) has
    no equivalent in this project's CSS-Grid `.row`/`.col-*` system, and
    this is the only place it's been needed so far, so it's a plain
    `display: grid; grid-template-columns: repeat(2, 1fr);` here rather
    than a new generated utility — deliberately not reusing `.grid`
    either, since that utility's contract ("however many fit down to
    `--grid-min`") is different from `row-cols-2`'s ("always exactly 2").
  - **New discovery, not seen in this form before**: the stat value carries
    both `.why-split-stat-value` (block-specific, sets a valid
    `font-weight: var(--fw-bold)`) and the global `.stat-lg`
    (`_typography.scss`, sets `font-weight: var(--fw-extrabold)` — a token
    that **doesn't exist anywhere in the source's own tokens file**). An
    invalid `var()` with no fallback makes that one declaration drop out
    entirely rather than compute to some default, so the *other* class's
    valid declaration wins the cascade instead — confirmed live
    (`font-weight` computes to `700`, not `800`, on `hts.local`). `.stat-lg`
    itself isn't ported as a class (nothing else needs it, and it's
    partially broken) — the parts of it that *are* valid (font-size,
    letter-spacing) are folded directly into `.why-split-stat-value`'s own
    rule instead, matching the actually-rendered result rather than either
    class's presumed intent.
- **`CB Client Projects Gallery` block** (`blocks/cb-client-projects-gallery/`)
  — a lightbox image gallery, either a repeating five-tile mosaic (default)
  or one large feature tile plus half-width tiles with hover captions
  (`layout` select). Two things genuinely new here, not extensions of an
  existing pattern:
  - **The `images` field is a real multi-image gallery picker**, the first
    of its kind in this project's block editor (every earlier image field —
    `CB Home Hero`, `CB Configurator`, `CB Product / Project Hero` — was
    single-image `MediaUpload`). Built directly in this block's own
    `edit.js` rather than as a new `blocks/_shared/` component, matching
    this project's standing rule of not generalising until a second real
    need shows up (the same reasoning `RepeaterField` itself followed —
    built concrete for `CB Marquee Stats` first, generalised only once `CB
    Specs` needed it too). Uses `MediaUpload`'s built-in `gallery` prop
    (the same mechanism core's own Gallery block uses) for add/remove/
    reorder, and `useSelect` + `@wordpress/core-data`'s `getMedia()` to
    fetch thumbnails for the live preview grid — a new data dependency
    this project hadn't needed before, auto-detected into
    `build/index.asset.php`'s dependency array by `wp-scripts`, no manual
    wiring required.
  - **First block needing a third-party JS library beyond GSAP**:
    [GLightbox](https://glightbox.com/) (lightbox on click, from the same
    CDN source already used for GSAP). Enqueued unconditionally in
    `cb_hts_js_2026_enqueue_scripts()` (`inc/enqueue.php`), matching GSAP's
    own treatment — the *library* loads site-wide once a block needs it,
    while the small *init* snippet that actually calls `GLightbox({...})`
    only prints when this specific block rendered. Unlike the marquee
    scripts (a separate enqueued file, gated with `has_block()` in
    `inc/blocks.php`), this block's init script is a `wp_footer` closure
    hooked *from inside `render.php` itself* — ported verbatim from the
    source, which self-gates for free (the closure is only ever registered
    if `render.php` actually ran with images to show), so no separate
    `has_block()` check was needed here the way the marquee script needed
    one.
  Also needed two new generated utilities, both straightforward additions
  to the existing systems rather than one-off overrides:
  - **`offset-{bp}-{n}`**, for the header row's `offset-md-1` — added to
    `src/build/generate-utilities.js` alongside the existing `col-{bp}-{n}`
    loop, implemented as `margin-inline-start` (the same technique
    Bootstrap itself uses, so it behaves identically on this project's
    CSS-Grid `.row` as it would on Bootstrap's flex one).
  - The mosaic layout's per-tile `grid-column`/`grid-row` values are
    **inline styles computed in PHP** (ported as a direct, literal copy of
    the source's index-math — five-tile repeating cycle, alternating
    left/right-heavy pattern every other cycle), not a CSS class or grid
    utility. This isn't a case for a generated utility the way `col-*`/
    `offset-*` are — the position of every tile depends on its numeric
    index among *all* images in the gallery, which can only be computed
    once, in PHP, at render time.
- **`CB Image CTA` block** (`blocks/cb-image-cta/`) — full-bleed dark image
  band, centered eyebrow/headline/content, up to two CTAs, same
  scroll-parallax technique as `CB Home Hero`/`CB Product / Project Hero`
  (own `--image-cta-parallax-y` custom property, wider ±240px range instead
  of hero's ±120px — ported as measured, not homogenised). Added
  `.btn-outline-light` to `src/css/forms.css` (a light-bordered variant of
  the existing `.btn-outline-dark`, needed here since this block's
  background is dark navy rather than white) — same simplified-from-
  Bootstrap-CSS-vars treatment as `.btn-outline-dark`.
  **Another latent-source-bug instance**, same family as `CB Specs`' `$bg`
  and `CB Why Split`'s `g-xl-6`, but this time in literal markup rather than
  a computed/utility value: the source's eyebrow is
  `class="eyebrow eyebrow--plain light center"` — `light` and `center` are
  missing the `eyebrow--` prefix (`eyebrow--light` is a real modifier,
  `eyebrow--center` was never actually built), so neither matches any CSS
  rule and both are silently inert. Confirmed live rather than assumed: the
  eyebrow computes to the default `--col-orange`, not the brighter
  `--col-orange-bright` that `.eyebrow--light` would actually give it, even
  sitting on this block's dark navy background where the brighter variant
  would read better. Centering is real, from a *different*, correctly-
  scoped rule (`.image-cta .eyebrow { justify-content: center; }`, ported
  as `.image-cta .eyebrow` in `cb-image-cta.css`) — not from the dead
  `center` class. Only `eyebrow--plain` (the one real, correctly-prefixed
  modifier) made it into this block's markup.

### Generic tabs pattern (Site-Wide Settings, and reusable beyond it)

The Site-Wide Settings page tabs its five sections (General, Social, Footer,
Tracking & Verification, Clients) — needed once the `repeater` field type
made individual sections (Clients especially) long enough that scrolling
past everything else to reach them got old. Built deliberately generic
rather than Settings-API-specific, since it's plausible a block could want
tabs one day too (e.g. a block with genuinely distinct groups of fields):

- **`js/tabs.js`** — framework-free vanilla JS (no jQuery; nothing here
  needs `wp.media` the way `gallery-field.js`/`repeater-field.js` do). Knows
  nothing about settings pages specifically — it just pairs up any
  `[data-tabs-target]` link with the `[data-tabs-panel]` sharing the same
  value, inside a `[data-tabs]` container, and toggles the panel's `hidden`
  attribute plus the link's `nav-tab-active` class. Reusable by construction:
  the only requirement is that markup contract, not any particular PHP or
  React structure around it.
- **Nav styling is WordPress core's own**, not custom CSS — `.nav-tab-wrapper`
  / `.nav-tab` / `.nav-tab-active` are already built into wp-admin (the same
  classes Import/Export and many plugin settings screens use), so the tab
  bar needed zero CSS of its own to look native. Only the panels needed a
  touch of inline `padding-top` for breathing room below the bar.
- **`cb_hts_js_2026_render_settings_page()`** builds one tab per registered
  section by reading `$wp_settings_sections['theme-general-settings']`
  directly (the same global `do_settings_sections()` reads internally) and
  looping it itself, calling `do_settings_fields( $page, $section_id )` once
  per section inside its own tab panel — `do_settings_sections()` always
  renders every section for a page in one continuous flow with no way to
  isolate one section at a time, so there was no way to reuse it here.
  Reading the existing global rather than adding a second, parallel
  tabs-config array keeps the section list/titles/order defined in exactly
  one place (the `add_settings_section()` calls already in
  `cb_hts_js_2026_register_settings_page()`).
- **Tabs are cosmetic only, not a second form** — every section's fields
  stay inside the one `<form>`, hidden panels included. A hidden ancestor
  doesn't remove its inputs from form submission (only `hidden`/`disabled`
  on the input itself would), so clicking "Save Changes" from any tab always
  saves every tab's fields together. Verified: saved from the Clients tab
  with the General tab's fields still populated from an earlier save, and
  both landed in the option correctly.
- If a future block genuinely needs tabbed fields in its own Inspector or
  canvas UI, `blocks/_shared/RepeaterField.js` is the model to follow for
  what a React sibling would look like — same interaction *concept* as
  `js/tabs.js` (a11y-correct show/hide keyed by a shared identifier), but
  its own separate implementation, the same way the block-editor and
  wp-admin repeaters are two implementations of one pattern rather than one
  shared one. Don't build that component speculatively before a real block
  needs it, the same reasoning as everything else in this file about not
  designing for hypothetical future requirements.

### Repeater pattern (for `CB Marquee Stats` and any future repeater field)

**Superseded design note:** an earlier version of this block split into two
blocks (`cb-marquee-stats` + `cb-marquee-stat-item`) using WordPress's
`InnerBlocks` to get add/remove/reorder for free, since ACF repeaters have no
native-blocks equivalent. That approach was deliberately abandoned — the
whole point of this skeleton is eradicating ACF, including its patterns of
thought, and turning one repeater field into two separate block
registrations (each row a real, independent entry in the block tree/List
View) was reintroducing ACF's absence as a structural workaround rather than
solving it. It also carried real bugs: a parent block's `save()` needs an
`InnerBlocks.Content` placeholder or newly-typed rows silently fail to
persist on save (hit and fixed once already), and any wrapper element
`save()` adds becomes literal `$content` inside the dynamic block's
`render.php`, breaking CSS that assumes the children are direct flex/grid
children. Don't resurrect the two-block/`InnerBlocks` approach.

The current pattern instead keeps the whole repeater as **one array
attribute on a single block**, edited through a shared, genuinely generic
React component:

1. **`blocks/_shared/RepeaterField.js`** — not part of any block's own
   `src/`, so it isn't picked up by `webpack.config.js`'s
   `blocks/*/src/index.js` glob as its own entry; it's imported into
   whichever blocks need it and gets bundled into each of their own
   `build/index.js`. Takes `label`, `value` (the current rows array),
   `onChange`, a declarative `fields` array (`{ name, label, type: 'text' |
   'textarea' | 'image', help }`), and `emptyRow` (the shape of a freshly
   added row). Renders add/move-up/move-down/remove controls itself — no
   native Gutenberg UI is reused here since none exists for this outside
   `InnerBlocks`.
2. A repeater-shaped block declares one `array`-typed attribute (e.g.
   `items`) with `"default": []` in `block.json`, and its `edit.js` is just
   `<RepeaterField value={ items } onChange={ ( v ) => setAttributes( {
   items: v } ) } fields={ FIELDS } emptyRow={ EMPTY_ROW } />` — see
   `blocks/cb-marquee-stats/src/edit.js`.
3. `save()` goes back to the trivial `() => null` every other leaf dynamic
   block uses — no `InnerBlocks.Content`, no wrapper-element bugs, since the
   array attribute serializes straight into the block's own JSON comment
   like any other attribute.
4. `render.php` reads `$attributes['items']` directly and loops it with a
   plain `foreach`, filtering out fully-empty rows the way the old
   `have_rows()` loop's `continue` did.

This is the same sub-field vocabulary (`text`/`textarea`/`image`) as the
`repeater` field type in `inc/options.php`'s Site-Wide Settings page — same
mental model, two separate concrete implementations, because one runs in the
block editor's React tree and the other in a plain wp-admin form with no
React involved at all. Don't try to unify them into one shared
implementation; keep the vocabulary consistent instead.

For a block-specific animation script that needs an external dependency
(here, GSAP): don't use block.json's `"viewScript": "file:./view.js"` for
this — that auto-registers the script with no dependency array, and a CDN
global like `gsap` (enqueued site-wide, commented-out-by-default in
`inc/enqueue.php` until this block needed it) isn't guaranteed to load
first. Instead, register + conditionally enqueue by hand
(`cb_hts_js_2026_enqueue_marquee_view_script()` in `inc/blocks.php`, hooked
to `wp_enqueue_scripts`, gated on `has_block( 'cb-hts-js-2026/cb-marquee-stats' )`
so it only loads on pages that actually use the block), with `array( 'gsap' )`
as an explicit dependency so it prints after.

### Bugs found and fixed during this migration (don't rediscover these)

- **`setup.sh`'s rename was silently incomplete.** Its `old_prefix`/
  `old_prefix_upper`/`old_prefix_pascal` variables were hardcoded as
  `lc_skeleton`/`LC_SKELETON`/`LC_Skeleton` in the skeleton repo, but the
  actual codebase prefix is `lc_js_skeleton`/`LC_JS_SKELETON`/
  `LC_JS_Skeleton` (missing `js_`) — every PHP function/constant/class name
  silently survived every rename to date. Also silently missing: the
  chmod-before-`mv` fix for the temp header file (mktemp's 0600 default
  permissions left `header.php` unreadable by the webserver — a live 500
  error) and the folder self-rename step, both of which had only ever been
  applied locally on this derived project and never pushed back to the
  skeleton. All fixed and pushed to `lc-js-skeleton2026` upstream, along
  with a matching fix for the editor-chrome CSS class kebab prefix (which
  uses a bare `lc-js-skeleton-` form the rename never covered either, since
  it's neither `old_slug` — which carries a trailing `2026` — nor
  `old_prefix` in its kebab form).
- **The settings-option rename orphaned live data.** The blanket
  `lc_js_skeleton_` → `cb_hts_js_2026_` rename changed
  `LC_JS_SKELETON_SETTINGS_OPTION`'s *value* (the option's storage key in
  the database) from `lc_js_skeleton_site_settings` to
  `cb_hts_js_2026_site_settings`, but the already-configured live option row
  stayed under the old key. The site silently started reading/writing a
  brand-new empty option under the new key, discarding every setting already
  entered in wp-admin (email, phone, utility message, social URLs) with no
  error — the bug only surfaced because the footer stopped showing phone/
  email. Fixed by merging the old option's data into the new key and
  deleting the old row. **Lesson: renaming a `define()`'d option-name
  constant is a data migration, not just a find-and-replace** — check for
  a live WP option under the old key whenever a settings-storage constant
  changes.
- **`inc/options.php` had a stray closing brace outside PHP tags** (`?>`
  before the function's own closing `}` instead of after it) — the brace was
  literally being echoed as page output and the function never closed,
  producing a fatal "critical error" on the whole settings page.
- **Double `.container` nesting squeezed every block ~32px too narrow.**
  `page.php` wrapped the entire `the_content()` output in its own
  `<div class="container">`, but individual blocks (the hero, and core
  blocks via `cb_hts_js_2026_core_block_type_args()`'s wrap-in-container
  render_callback) already provide their own — so a full-bleed block like
  the hero ended up nested two `.container`s deep, and the inner one's own
  `max-width: 1360px` was constrained by its 1328px-wide parent instead of
  the real 1360px. Symptom was subtle: it looked like "the H1 wraps onto an
  extra line" rather than an obviously broken layout. Fixed by removing the
  outer `.container` from `page.php` — blocks are responsible for their own
  width/full-bleed, the page template shouldn't pre-constrain them.
  `single.php`/`index.php` have the same redundant wrap but are not
  currently in active use for this site (it's built from Pages, not blog
  Posts) — not fixed yet, flagging as a known latent issue if those
  templates ever come into use.
- **`npm run watch`/`watch-bs` never watched block JS at all** — only
  `watch-run-css` (theme CSS + `src/blocks/*.css`) and `watch-run-js` (theme
  JS) were included in the parallel task list; `blocks:start` (the
  webpack watcher for `blocks/*/src/`) was defined as a script but never
  actually wired into either watch command. Editing a block's `edit.js` and
  expecting a rebuild silently did nothing. Fixed by adding `blocks:start`
  to both.

### What's left (see task list / ask for current status — this changes often)

Sequencing was deliberately: easy blocks first, then design the repeater
pattern once a real repeater-shaped block demanded it (done, see above),
then work through the remaining ~12 blocks that need it. As of the last
session: `CB Home Hero`, `CB Marquee Stats`, `CB Intro`, `CB Selected
Clients`, `CB Applications Grid`, `CB Configurator`,
`CB Product / Project Hero`, `CB Specs`, `CB Downloads`, `CB Why Split`,
`CB Client Projects Gallery`, and `CB Image CTA` are done; product-page-
building is underway (`CB Products Grid` next); `cb-heading` (genuinely
simple, no repeater) is still pending too; the larger set of blocks with
repeaters/relationships/`WP_Query` loops/theme-helper dependencies come
after that, now unblocked by both
repeater patterns above
(block-attribute
`RepeaterField` and the options-page `repeater` setting type).
