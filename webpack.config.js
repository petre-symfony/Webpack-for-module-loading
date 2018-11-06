const path = require('path');
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ExtracTextPlugin = require('extract-text-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const WebpackChunkHash = require('webpack-chunk-hash');

const useDevServer = false;
const useVersioning = true;
const publicPath = useDevServer ? 'http://localhost:8080/build/' : '/build/';
const isProduction = process.env.NODE_ENV === 'prod';
const useSourceMaps = !isProduction;

const styleLoader = {
	loader: 'style-loader',
	options: {
		sourceMap: useSourceMaps
	}
}

const cssLoader = {
	loader: 'css-loader',
	options: {
		sourceMap: useSourceMaps,
		minimize: isProduction
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
		sourceMap:useSourceMaps
	}
}

const webpackConfig = {
	entry: {
		rep_log: './assets/js/rep_log.js',
		login: './assets/js/login.js',
		layout: './assets/js/layout.js'
	},
	output: {
		path: path.resolve(__dirname, 'public', 'build'),
		filename: useVersioning ? "[name].[chunkhash:6].js" : "[name].js",
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
		new ExtracTextPlugin(
			useVersioning ? "[name].[contentHash:6].css" : "[name].css",
		),
		new ManifestPlugin({
			// always dump manifest
			writeToFileEmit: true,
			basePath: 'build/'
		}),
		//allows [chunkhash]
		new WebpackChunkHash()
	],
	devtool: useSourceMaps ? 'inline-source-map' : false,
	devServer: {
		contentBase: './public',
		headers: { 'Access-Control-Allow-Origin': '*' },
	}
}

if (isProduction){
	webpackConfig.plugins.push(
		new webpack.optimize.UglifyJsPlugin()
	);
	webpackConfig.plugins.push(
		// passes these options to all loaders
		// but we should really pass these ourselves
		new webpack.LoaderOptionsPlugin({
			minimize: true,
			debug: false
		})
	);
	webpackConfig.plugins.push(
		new webpack.DefinePlugin({
			'process.env.NODE_ENV': JSON.stringify('prod')
		})
	);
}

module.exports = webpackConfig;