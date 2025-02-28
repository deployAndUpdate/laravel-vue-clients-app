import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

// https://vitejs.dev/config/
export default defineConfig({
    // plugins: [vue()],
    // server: {
    //     proxy: {
    //         '/app': 'http://localhost', // Прокси для Laravel API, если требуется
    //     }
    // }

    plugins: [
        vue(),
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
    ],
});
