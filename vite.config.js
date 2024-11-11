import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from 'tailwindcss';
import path from 'path';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        })
    ],
    css: {
        postcss: {
          plugins: [tailwindcss()],
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js/src')
        }
    }
});
