'use-strict';

const webpack = require('webpack');

module.exports = {
  // devtool: 'source-map',

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
          presets: ['es2015'],
          plugins: ['transform-runtime']
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
}
