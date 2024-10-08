import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Archivo', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            accent: {
                100: '#F6606E',
                40: '#f7acb3',
                25: '#ffc9ce',
            },
            secondary: '#262c3a',
            black: colors.black,
            white: colors.white,
            gray: colors.gray,
        },
    },

    plugins: [
        forms,
        
    ],
};
