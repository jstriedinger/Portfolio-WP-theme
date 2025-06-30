const path = require('path');
const {merge} = require('webpack-merge');
const common = require('./webpack.common.js');
const BrowserSyncPlugin = require('browser-sync-v3-webpack-plugin');



module.exports = merge(common, {
  mode: 'development',
  watch: true,
  plugins: [
    new BrowserSyncPlugin({
        files: [
          './**/*.php',
          './**/*.twig'
        ],
        host: 'localhost',
        port: 3000,
        proxy: 'http://jstriedinger.local/',
        logPrefix: 'webpack',
        logLevel: 'debug',
        ghostMode: false
    })
  ]
});