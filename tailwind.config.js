import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gold: {
                    50: '#fdf8ed',
                    100: '#f9efd0',
                    200: '#f2dd9f',
                    300: '#e9c668',
                    400: '#e0b13d',
                    500: '#C8A951',
                    600: '#a88a35',
                    700: '#8c702b',
                    800: '#745d27',
                    900: '#634e23',
                },
            },
        },
    },

    plugins: [forms],
};
