<?php

namespace Aerni\LivewireForms\Commands;

use Aerni\LivewireForms\Facades\Component;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;

class MakeView extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:view {name?}';

    protected $description = 'Create a new Livewire form view';

    public function handle(): void
    {
        $view = $this->argument('name') ?? $this->ask('What do you want to call the view?', Component::defaultView());
        $stub = File::get(__DIR__.'/../../resources/stubs/form.blade.php');
        $filename = "{$view}.blade.php";
        $path = resource_path("views/livewire/forms/{$filename}");

        if (! File::exists($path) || $this->confirm("A view with the name <comment>$filename</comment> already exists. Do you want to overwrite it?")) {
            File::ensureDirectoryExists(resource_path('views/livewire/forms'));
            File::put($path, $stub);
            $this->line("<info>[✓]</info> The view was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
