import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/front.css",
                "resources/js/app.js",
                "resources/js/front.js",
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
    build: {
        // Mengoptimalkan bundle untuk produksi
        minify: "terser",
        chunkSizeWarningLimit: 1000,
    },
    server: {
        // Mengaktifkan hot module replacement
        hmr: {
            host: "localhost",
        },
    },
});
