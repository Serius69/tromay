import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    build: {
        // Single chunk below 500 kB — no chunking warnings for this app
        chunkSizeWarningLimit: 500,

        rollupOptions: {
            output: {
                // Separate vendor libs from app code for better cache reuse
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                },
            },
        },

        // Minify with esbuild (default, fast) — no terser needed
        minify: 'esbuild',

        // Generate source maps only in dev; CI sets NODE_ENV=production
        sourcemap: process.env.NODE_ENV !== 'production',
    },

    // Reduce noise in CI logs
    logLevel: process.env.CI ? 'warn' : 'info',
});
