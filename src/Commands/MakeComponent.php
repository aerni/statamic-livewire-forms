<?php

namespace Aerni\LivewireForms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;

class MakeComponent extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:component {name}';
    protected $description = 'Create a new Livewire form component';

    public function handle(): void
    {
        $filename = Str::studly($this->argument('name'));
        $stub = File::get(__DIR__ . '/../../resources/stubs/form.php');
        $stub = str_replace('DummyForm', $filename, $stub);
        $path = app_path("Http/Livewire/{$filename}.php");

        if (! File::exists($path) || $this->confirm("A component with the name <comment>$filename</comment> already exists. Do you want to overwrite it?")) {
            File::ensureDirectoryExists(app_path('Http/Livewire'));
            File::put($path, $stub);
            $this->line("<info>[✓]</info> The component was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path() . '/', '', $path);
    }
}
