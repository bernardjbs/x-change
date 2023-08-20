import { fileURLToPath, URL } from "node:url";
import laravel from "laravel-vite-plugin";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [laravel(["resources/css/app.css", "resources/js/app.js"]), vue()],
    resolve: {
        alias: {
            "@": fileURLToPath(new URL("./src", import.meta.url)),
        },
    },
});
