const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');

const dirApp = path.join(__dirname, 'src/js');
const dirStyles = path.join(__dirname, 'src/sass');

module.exports = {
  entry: [
    path.join(dirApp, 'app.js'),
    path.join(dirStyles, 'index.scss'),
  ],
  output: {
    filename: './js/[name].js',
    path: path.resolve(__dirname, 'assets'),
    clean: true,
  },
  resolve: {
    modules: [
      dirApp,
      dirStyles,
      path.join(__dirname, 'node_modules')
    ]
  },
  externals: {
    jquery: 'jQuery'
  },
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          MiniCssExtractPlugin.loader,
          { loader: 'css-loader', options: { url: false, sourceMap: true } },
          { loader: 'postcss-loader', options: { sourceMap: true } },
          { loader: 'sass-loader', options: { sourceMap: true } }
        ]
      },
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: ['babel-loader']
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({ filename: "[name].css" }),
    new CopyWebpackPlugin({
      patterns: [
        { from: 'src/fonts', to: 'webfonts' },
        { from: 'src/images', to: 'images' }
      ],
    })
  ]
};
