const path = require('path');
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const styleLoader = {
	loader: 'style-loader',
	options: {
		sourceMap: true
	}
}

const cssLoader = {
	loader: 'css-loader',
	options: {
		sourceMap: true
	}
}

const sassLoader = {
	loader: 'sass-loader',
	options: {
		sourceMap: true
	}
}

const resolveUrlLoader = {
	loader: 'resolve-url-loader',
	options: {
		sourceMap: true
	}
}

module.exports = {
	entry: {
		rep_log: './assets/js/rep_log.js',
		login: './assets/js/login.js',
		layout: './assets/js/layout.js'
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
					styleLoader,
					cssLoader
				]
			},
			{
				test: /\.scss$/,
				use: [
					styleLoader,
					cssLoader,
					resolveUrlLoader,
					sassLoader
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
			$: 'jquery',
			'window.jQuery': 'jquery'
		}),
		new CopyWebpackPlugin([
			//copies to [output]/static
			{ from: './assets/static', to: 'static' }
		]),
		new webpack.optimize.CommonsChunkPlugin({
			name: 'vendor',
			minChunks: function (module) {
				return module.context && module.context.indexOf("node_modules") !== -1;
			}
		})
	],
	devtool: 'inline-source-map'
}