import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        rollupOptions: {
            input: {
                'livewire-forms': 'resources/js/livewire-forms.js',
                'form': 'resources/js/form.js',
                'filepond': 'resources/js/filepond.js',
                'grecaptcha': 'resources/js/grecaptcha.js',
            },
            output: {
                entryFileNames: 'js/[name].js',
                assetFileNames: 'css/[name].css'
            }
        },
    }
});
