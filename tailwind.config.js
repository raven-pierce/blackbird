const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Public Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Bitter', ...defaultTheme.fontFamily.serif],
                mono: ['Roboto Mono', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    darkMode: 'class',

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ],
};
