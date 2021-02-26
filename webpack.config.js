const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		'petition-block': './assets/js/petition-block.js'
	},
	output: {
		path: path.join(__dirname, './assets/js/output'),
		filename: '[name].js'
	}
}
