'use-strict';

const webpack = require('webpack');

module.exports = {
  devtool: 'source-map',
  entry: ['babel-polyfill', './scripts/js/source/main.js'],
  output: {
    filename: 'bundle.js'
  },

  module: {
    loaders: [
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        loader: 'eslint-loader',
        enforce: 'pre'
      },
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        loader: 'babel-loader',
        babelrc: false,
        query: {
          presets: ['es2015']
        }
      }
    ]
  },

  plugins: [
    new webpack.optimize.UglifyJsPlugin({
     compressor: { warnings: false },
     output: { comments: false },
     sourceMap: true
   })
  ]
};
