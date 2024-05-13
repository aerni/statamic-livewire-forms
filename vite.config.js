import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'resources/dist/js',
        lib: {
            entry: 'resources/js/livewire-forms.js',
            name: 'livewire-forms',
            fileName: () => 'livewire-forms.js',
        }
    }
});
