import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        react(),
        laravel({
            input: ['resources/css/app.css',
                    'resources/js/components/page/app.jsx',
                    'resources/js/components/page/dashboard.jsx',
                    'resources/js/components/page/verificar.jsx' 
                ],
            refresh: true,
        }),
    ],
});