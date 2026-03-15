import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/realtime-search.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            // Ensure jQuery is available for Select2
            'jquery': 'jquery/dist/jquery.js',
        }
    },
    optimizeDeps: {
        include: ['jquery', 'select2']
    }
});
