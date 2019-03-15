
const webpack = require('webpack');
const path = require('path');
const incstr = require('incstr');

const CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const extractSCSS = new ExtractTextPlugin('bb-bundle.css');

const devBuild = process.env.NODE_ENV !== 'production';
const nodeEnv = devBuild ? 'development' : 'production';

console.log('Build env: %s', nodeEnv);

const createUniqueIdGenerator = function() {
  const index = {};

  const generateNextId = incstr.idGenerator({
    alphabet: 'abcefghijklmnopqrstuvwxyz0123456789'
  });

  return (name) => {
    if (index[name]) {
      return index[name];
    }

    index[name] = generateNextId();
    return index[name];
  };
};

const uniqueIdGenerator = createUniqueIdGenerator();

const config = {
  entry: {
    'bb-bundle': [
      'babel-polyfill',
      './react/brochureBuilder.js'
    ]
  },
  output: {
    path: path.resolve(__dirname, 'web/assets/build/'),
    filename: '[name].js',
  },
  resolve: {
    extensions: ['.js', '.jsx'],
  },
  externals: {
    google: 'google'
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        loader: 'babel-loader',
        include: path.join(__dirname, 'react'),
        query: {
          plugins: [
            'transform-class-properties',
            'transform-async-to-generator'
          ],
          presets: [
            'es2015',
            'es2016',
            'react'
          ]
        }
      },
      {
        test: /\.scss$/i,
        include: path.join(__dirname, 'react'),
        use: extractSCSS.extract({
          fallback: 'style-loader',
          use: [
            {
              loader: 'css-loader',
              options: {
                minimize: !devBuild,
                modules: true,
                importLoaders: true,
                localIdentName: devBuild ? 'bb-[name]__[local]-[hash]' : 'bb-[hash]',
                getLocalIdent: (context, localIdentName, localName, options) => {
                  return 'bb-' + uniqueIdGenerator(context.resourcePath + '::' + localName) + 
                    (devBuild ? '___' + localName + '___' + 
                      context.resourcePath.replace(/^.+\/brochure-builder\//, '').replace(/\.s?css$/, '').replace(/\//g, '_') : '');
                }
              }
            },
            {
              loader: 'postcss-loader',
              options: {
                ident: 'postcss',
                plugins: (loader) => [
                  require('autoprefixer')()
                ]
              }
            },
            'sass-loader'
          ]
        })
      },
      {
        test: /\.(png|jpg|gif)$/,
        include: path.join(__dirname, 'react'),
        use: [
          {
            loader: 'file-loader',
            options: {
              name: 'images/bb/[name]-[hash].[ext]',
              publicPath: '/assets/build/'
            }
          }
        ]
      }
    ]
  },
  plugins: [
    extractSCSS,
    new CaseSensitivePathsPlugin(),
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: JSON.stringify(nodeEnv),
      },
    }),
  ].concat(devBuild ? [] : [
    new webpack.optimize.UglifyJsPlugin()
  ]),
}

module.exports = config;
