import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
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
