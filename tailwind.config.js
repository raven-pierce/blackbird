const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            screens: {
                '3xl': '1920px',
                '4xl': '2560px',
            },
        },
        animation: {
            blob: "blob 7s infinite",
        },
        keyframes: {
            blob: {
                '0%, 100%': { transform: 'scale(1) translateY(0)' },
                '33%': { transform: 'scale(1.2) translateY(10%) translateX(10%)' },
                '66%': { transform: 'scale(1.4) translateY(-5%)  translateX(-10%)' },
            }
        }
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography'), require('tailwindcss-animation-delay')],
};
