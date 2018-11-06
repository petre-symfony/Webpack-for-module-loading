const path = require('path');
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ExtracTextPlugin = require('extract-text-webpack-plugin');

const useDevServer = false;
const publicPath = useDevServer ? 'http://localhost:8080/build/' : '/build/';

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
		publicPath: publicPath
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
				use: ExtracTextPlugin.extract({
					use: [
						cssLoader
					],
					//use this if css isn't extracted
					fallback: styleLoader
				})
			},
			{
				test: /\.scss$/,
				use: ExtracTextPlugin.extract({
					use: [
						cssLoader,
						resolveUrlLoader,
						sassLoader
					],
					//use this if css isn't extracted
					fallback: styleLoader
				})
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
			name: [
				// "layout" is an entry file
				// anything included in layout, is not included in other output files
				'layout',
				// dumps the manifest into a separate file
				'manifest'
			],
			minChunks: Infinity
		}),
		new ExtracTextPlugin('[name].css')
	],
	devtool: 'inline-source-map',
	devServer: {
		contentBase: './public',
		headers: { 'Access-Control-Allow-Origin': '*' },
	}
}