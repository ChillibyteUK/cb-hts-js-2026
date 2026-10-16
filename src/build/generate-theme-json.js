// generate-theme-json.js
// Node.js script to parse CSS custom-property tokens and generate theme.json
// for the WordPress block editor.
//
// Usage:
//   npm run generate-theme-json
//
// Reads the :root custom properties from src/css/tokens.css and generates
// theme.json with a color palette and font sizes for Gutenberg.
//
// Looks for:
// - Color variables: --col-* (excluding aliases like --col-primary that just
//   point at another color — only "real" palette colors get listed)
// - Font size variables: --fs-*

const fs = require('fs');
const path = require('path');
const { readTokensFromFile } = require('./read-tokens');

const tokensFile = path.join(__dirname, '../css/tokens.css');
const blocksDir = path.join(__dirname, '../../blocks');

// A palette colour counts as "light" at or above this WCAG relative luminance.
// The HTS palette has a clean gap between 0.35 and 0.61, so 0.5 splits it.
const LIGHT_LUMINANCE = 0.5;

function relativeLuminance(hex) {
	const h = hex.replace('#', '');
	const full = h.length === 3 ? h.split('').map((c) => c + c).join('') : h;
	const [r, g, b] = [0, 2, 4].map((i) => {
		const c = parseInt(full.slice(i, i + 2), 16) / 255;
		return c <= 0.03928 ? c / 12.92 : ((c + 0.055) / 1.055) ** 2.4;
	});
	return 0.2126 * r + 0.7152 * g + 0.0722 * b;
}

// Blocks that offer a background colour picker. Only blocks whose default
// background is light have one (dark/gradient blocks don't), so every block
// found here gets the light-only palette.
function blocksWithBackgroundSupport() {
	return fs
		.readdirSync(blocksDir)
		.map((dir) => path.join(blocksDir, dir, 'block.json'))
		.filter((file) => fs.existsSync(file))
		.map((file) => JSON.parse(fs.readFileSync(file, 'utf8')))
		.filter((block) => block.supports?.color?.background === true)
		.map((block) => block.name)
		.sort();
}
const themeJsonFile = path.join(__dirname, '../../theme.json');

function buildThemeJson(tokens) {
	// Palette colors: --col-{slug} whose value is a literal color, not a
	// var() alias (aliases like --col-primary: var(--col-blue-700) point at
	// one of the literal ones, so they'd just be a duplicate palette entry).
	const colors = Object.entries(tokens)
		.filter(([key, value]) => key.startsWith('col-') && !value.startsWith('var('))
		.map(([key, value]) => ({
			name: key.replace('col-', ''),
			slug: key.replace('col-', ''),
			color: value,
		}));

	const fontSizes = Object.entries(tokens)
		.filter(([key]) => key.startsWith('fs-'))
		.map(([key, value]) => {
			const slug = key.replace('fs-', '');
			return { name: slug, slug, size: value };
		});

	const palette = colors.map((color) => ({ ...color, origin: 'theme' }));
	const lightPalette = palette.filter(
		(color) => /^#[0-9a-f]{3}([0-9a-f]{3})?$/i.test(color.color) && relativeLuminance(color.color) >= LIGHT_LUMINANCE
	);
	const blocks = Object.fromEntries(
		blocksWithBackgroundSupport().map((name) => [
			name,
			{ color: { custom: false, palette: lightPalette } },
		])
	);

	return {
		version: 2,
		settings: {
			appearanceTools: true,
			color: {
				defaultPalette: false,
				palette,
			},
			blocks,
			typography: { fontSizes },
		},
	};
}

function main() {
	if (!fs.existsSync(tokensFile)) {
		console.error('Tokens file not found:', tokensFile);
		process.exit(1);
	}
	const tokens = readTokensFromFile(tokensFile);
	const themeJson = buildThemeJson(tokens);
	fs.writeFileSync(themeJsonFile, JSON.stringify(themeJson, null, 2));
	console.log('theme.json generated successfully.');
}

main();
