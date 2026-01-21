import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // assets globales
                'resources/css/app.css',
                'resources/js/app.js',

                // assets del módulo Citas
                'resources/css/appointments.css',
                'resources/js/appointments.js',
                'resources/js/day_limits.js',
            ],
            refresh: true,
        }),
    ],

    // ayuda a Vite con FullCalendar v6
    optimizeDeps: {
        include: [
            '@fullcalendar/core',
            '@fullcalendar/daygrid',
            '@fullcalendar/timegrid',
            '@fullcalendar/list',
            '@fullcalendar/interaction',
        ],
    },
})
