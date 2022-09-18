module.exports = {
    important: true,
    content: [
        "./resources/views/vendor/livewire-ui-modal/modal.blade.php",
        "./resources/views/front/**/*.blade.php",
        "./resources/views/livewire/front/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'base'
        }),
    ],
}
