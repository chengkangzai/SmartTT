module.exports = {
    important: true,
    content: [
        "./resources/views/front/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
