// Webpack configuration for server bundle
process.env.NODE_ENV = 'production';

const webpack = require('webpack')

const path = require('path')
const devBuild = process.env.NODE_ENV !== 'production'
const nodeEnv = devBuild ? 'development' : 'production'

module.exports = {

    // the project dir
    context: __dirname,
    entry: {
        'server-bundle' : [ 'babel-polyfill', './react/serverRegistration.js' ],
    },
    output: {
        path: path.resolve(__dirname, 'app/Resources/webpack/'),
        filename: '[name].js'
    },
    resolve: {
        extensions: [ '.js', '.jsx' ],
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: JSON.stringify(nodeEnv),
            },
        }),
        new webpack.ProvidePlugin({
/*            _: 'lodash',
            $: 'jquery',
            'jQuery'              : 'jquery',
            'window.jQuery'       : 'jquery',*/
        })
    ],
    module: {
        loaders: [
            // { test: require.resolve('jquery'), loader: 'expose-loader?$!expose-loader?jQuery' },
            { test: /\.jsx?$/, loader: 'babel-loader', exclude: /node_modules/,     query:
                {
                    presets:['react']
                } },
        ],
    },
}
