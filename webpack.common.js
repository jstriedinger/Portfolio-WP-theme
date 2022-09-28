const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const {CleanWebpackPlugin} = require('clean-webpack-plugin')

//const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const dirApp = path.join(__dirname,'src/js')
const dirStyles = path.join(__dirname,'src/sass')

const config = {
  //context: path.resolve(__dirname, "src"),
  // configurations here
  entry: [
    path.join(dirApp,'app.js'),
    path.join(dirStyles,'index.scss'),
  ],
  output: {
    path: path.join(__dirname,'assets')
  },
  resolve: {
    modules: [
        dirApp,
        dirStyles,
        path.join(__dirname,'node_modules')
    ]
  },
  output: {
    filename: './js/[name].js',
    // Output path using nodeJs path module
    path: path.resolve(__dirname, 'assets')
  },
  // Adding jQuery as external library
  externals: {
    jquery: 'jQuery'
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
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
        use: [
          {
              loader: 'babel-loader',
              /*options: {
                  presets: ["@babel/preset-env"]
              }*/   
          }
        ]
      }
    ]
  },
  plugins: [
    
    require('autoprefixer'),
    new MiniCssExtractPlugin({filename: "[name].css"}),
    new CleanWebpackPlugin(),
    new CopyWebpackPlugin({
      patterns: [
        { from: 'src/fonts', to: 'webfonts' }
      ],
    })  
  ]
};

module.exports = config;