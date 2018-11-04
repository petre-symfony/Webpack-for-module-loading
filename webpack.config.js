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
		filename: "[name].js",
		publicPath: "/build/"
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						cacheDirectory: true
					}
				}
			},
			{
				test: /\.css$/,
				use: [
					'style-loader',
					'css-loader'
				]
			},
			{
				test: /\.(png|jpg|jpeg|gif|ico|svg)$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[name]-[hash:6].[ext]'
						}
					}

				]
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
          use: [
	          {
		          loader: 'file-loader',
		          options: {
			          name: '[name]-[hash:6].[ext]'
		          }
	          }
	        ]
      }
		]
	},
	plugins: [
		new webpack.ProvidePlugin({
			jQuery: 'jquery',
			$: 'jquery'
		}),
	]
}