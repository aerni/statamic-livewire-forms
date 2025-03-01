import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'resources/dist',
        lib: {
            entry: 'resources/js/livewire-forms.js',
            name: 'livewire-forms',
            fileName: 'js/livewire-forms',
            cssFileName: 'css/livewire-forms',
            formats: ['es'],
        }
    }
});
