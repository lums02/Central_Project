import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'public/build',
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',
                home: 'resources/js/home.js',
                dashboard: 'resources/js/dashboard.js',
                dashboardCSS: 'resources/css/dashboard.css', // CSS dashboard
                loginCSS: 'resources/css/login.css',           // CSS login
                registerCSS: 'resources/css/register.css'           // CSS register
            }
        }
    },
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    }
});
