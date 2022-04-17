const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", "Open Sans", ...defaultTheme.fontFamily.sans],
                serif: ["Roboto Serif", ...defaultTheme.fontFamily.serif],
                mono: ["JetBrains Mono", ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("tailwindcss-animation-delay"),
    ],
};
