const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const externals = require("./config/externals");
const babelPreset = require("./config/babel-preset");
const LodashModuleReplacementPlugin = require('lodash-webpack-plugin');
// const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = {
	entry: {
		"./c9-admin.build": "./admin/src/js/c9-admin.js",
	},
	output: {
		path: path.resolve(__dirname, "admin/dist"),
		filename: "[name].js",
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: "c9-admin.build.css",
		}),
		new LodashModuleReplacementPlugin,
		// new BundleAnalyzerPlugin(),
	],
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				use: {
					loader: "babel-loader",
					options: {
						// @remove-on-eject-begin
						babelrc: false,
						presets: [babelPreset],
						// @remove-on-eject-end
						// This is a feature of `babel-loader` for webpack (not Babel itself).
						// It enables caching results in ./node_modules/.cache/babel-loader/
						// directory for faster rebuilds.
						cacheDirectory: true,
					},
				},
			},
			{
				test: /\.scss$/,
				use: [
					MiniCssExtractPlugin.loader,
					"css-loader", // translates CSS into CommonJS
					"sass-loader", // compiles Sass to CSS, using Node Sass by default
				],
			},
		],
	},
	stats: {
		colors: true,
	},
	externals: externals,
};
