const path = require('path');
const webpack = require('webpack');

module.exports = {
	entry: {
		rep_log: './public/js/rep_log.js',
		login: './public/js/login.js',
		layout: './public/js/layout.js'
	},
	output: {
		path: path.resolve(__dirname, 'public', 'build'),
		filename: "[name].js"
	},
	plugins: [
		new webpack.ProvidePlugin({
			jQuery: 'jquery',
			$: 'jquery'
		}),
	]
}