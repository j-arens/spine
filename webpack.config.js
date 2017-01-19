'use-strict';

const webpack = require('webpack');
const nodeEnv = process.env.NODE_ENV || 'production';

module.exports = {
  devtool: 'source-map',
  entry: ['./src/scripts/js/main.js'],
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
          presets: ['es2015'],
          plugins: ['transform-runtime']
        }
      }
    ]
  },

  plugins: [
    new webpack.optimize.UglifyJsPlugin({
     compressor: { warnings: false },
     mangle: { except: ['window.dpiSmMenuEditor'] },
     output: { comments: false },
     sourceMap: true
   }),
   new webpack.DefinePlugin({
     'process.env': { NODE_ENV: JSON.stringify(nodeEnv) }
   }),
   new webpack.optimize.OccurrenceOrderPlugin()
  ]
}
