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
                    'resources/js/components/page/verificar.jsx',                    
                    'resources/js/components/page/errors/e401.jsx',
                    'resources/js/components/page/errors/e403.jsx',
                    'resources/js/components/page/errors/e404.jsx',
                    'resources/js/components/page/errors/e405.jsx',
                    'resources/js/components/page/errors/e419.jsx',
                    'resources/js/components/page/errors/e429.jsx',
                    'resources/js/components/page/errors/e500.jsx',
                    'resources/js/components/page/errors/e503.jsx',
                    'resources/js/components/page/servicioEspecial.jsx',
                    'resources/js/components/page/errors/upMantenimiento.jsx',
                ],
            refresh: true,
        }),
    ],
});