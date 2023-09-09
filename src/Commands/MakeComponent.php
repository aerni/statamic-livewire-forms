<?php

namespace Aerni\LivewireForms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Console\RunsInPlease;

class MakeComponent extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:component {name?}';

    protected $description = 'Create a new Livewire form component';

    public function handle(): void
    {
        $name = $this->argument('name') ?? $this->ask('What do you want to call the component?');
        $name = Str::of($name)->endsWith('Form') ? $name : Str::of($name)->append('Form')->__toString();
        $filename = Str::studly($name);

        $stub = File::get(__DIR__.'/../../resources/stubs/DummyForm.php');
        $stub = str_replace('DummyForm', $filename, $stub);
        $path = app_path("Livewire/{$filename}.php");

        if (! File::exists($path) || $this->confirm("A component with the name <comment>$filename</comment> already exists. Do you want to overwrite it?")) {
            File::ensureDirectoryExists(app_path('Livewire'));
            File::put($path, $stub);
            $this->line("<info>[✓]</info> The component was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
