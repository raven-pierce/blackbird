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
                sans: ['Inter', 'Open Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Roboto Serif', ...defaultTheme.fontFamily.serif],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            keyframes: {
                blurBackground: {
                    '0%, 100%': { transform: 'scale(1) translateY(0)' },
                    '33%': { transform: 'scale(1.2) translateY(10%) translateX(10%)' },
                    '66%': { transform: 'scale(1.4) translateY(-5%)  translateX(-10%)' },
                }
            },
            animation: {
                'blurBackground': 'blurBackground 4s infinite',
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography'), require('tailwindcss-animation-delay')],
};
