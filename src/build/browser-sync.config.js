module.exports = {
	proxy: 'hts.local/',
	host: 'hts.local',
	open: 'external',
	notify: false,
	files: ['./css/*.min.css', './js/*.min.js', './**/*.php'],
};
