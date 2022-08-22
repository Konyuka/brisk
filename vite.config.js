import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    build: {
        rollupOptions: {
            external: 'ziggy'
        }
    },
    resolve: {
        alias: {
            'ziggy': path.resolve(__dirname, 'vendor/tightenco/ziggy/dist/vue.m.js')
            // 'ziggy': path.resolve(__dirname, 'vendor/tightenco/ziggy/src/js')
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
