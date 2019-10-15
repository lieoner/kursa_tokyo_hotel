const path = require('path');
const webpack = require('webpack');

module.exports = {
    entry: {
        index: './src/js/index.js',
        admin: './src/js/admin/admin-index.js',
    },

    output: {
        filename: 'js/[name]-bundle.js',
        path: path.resolve(__dirname, 'dist'),
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
        }),
    ],

    module: {
        rules: [
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader'],
            },
            {
                test: /\.(png|svg|jpg|gif|webp)$/,
                use: [{ loader: 'file-loader?name=/src/image/[name].[ext]' }],
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: [{ loader: 'file-loader?name=/src/font/[name].[ext]' }],
            },
        ],
    },
};
