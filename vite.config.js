import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    build: {
        rollupOptions: {
            external: 'ziggy'
        }
    },
    resolve: {
        alias: {
            'ziggy': path.resolve('/vendor/tightenco/ziggy/dist/vue.m.js'),
            // 'ziggy': path.resolve(__dirname, 'C:/xampp7/htdocs/pos/vendor/tightenco/ziggy/dist/vue.m.js'),
            path: "path-browserify",
        }
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    ssr: {
        noExternal: ['@inertiajs/server'],
    },
});
