const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .webpackConfig({
        plugins: [
            new webpack.DefinePlugin({
                'process.env.MIX_PUSHER_APP_KEY': JSON.stringify(process.env.MIX_PUSHER_APP_KEY),
                'process.env.MIX_PUSHER_APP_CLUSTER': JSON.stringify(process.env.MIX_PUSHER_APP_CLUSTER),
            }),
        ],
    });
