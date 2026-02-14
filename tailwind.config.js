import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                blue: {
                    50:  '#f0f5fa',
                    100: '#d9e8f3',
                    200: '#b2d1e8',
                    300: '#7fb5d8',
                    400: '#5399c6',
                    500: '#457cb0',
                    600: '#396894',
                    700: '#2c5173',
                    800: '#1e374e',
                    900: '#122230',
                },
            },
        },
    },

    plugins: [forms],
};
