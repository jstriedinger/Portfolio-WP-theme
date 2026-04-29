const path = require('path');
const {merge} = require('webpack-merge');
const common = require('./webpack.common.js');
const BrowserSyncPlugin = require('browser-sync-v3-webpack-plugin');



module.exports = merge(common, {
  mode: 'development',
  devtool: 'source-map',
  watch: true,
  plugins: [
    new BrowserSyncPlugin({
        files: [
          './**/*.php',
          './**/*.twig'
        ],
        host: '127.0.0.1',
        port: 3000,
        proxy: 'https://jstriedinger.local/',
        logPrefix: 'webpack',
        logLevel: 'debug',
        ghostMode: false
    })
  ]
});