/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "background-black": "#211F1F",
                success: "#5DFC5A",
                nav: "#333131",
            },
        },
    },
    plugins: [],
};
