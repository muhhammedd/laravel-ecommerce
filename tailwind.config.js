const defaultTheme = require('tailwindcss/defaultTheme');

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
                sans: ['Assistant', ...defaultTheme.fontFamily.sans],
                serif: ['Cormorant Garamond', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                'candle-green': '#727D61',
                'candle-light': '#F5F5F5',
                'candle-dark': '#1F1F1F',
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/aspect-ratio')],
};
