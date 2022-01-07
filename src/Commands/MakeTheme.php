<?php

namespace Aerni\LivewireForms\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Illuminate\Support\Facades\File;
use Aerni\LivewireForms\Facades\Component;

class MakeTheme extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:theme {name?}';
    protected $description = 'Create a new Livewire form theme';

    public function handle(): void
    {
        $theme = $this->argument('name') ?? $this->ask('What do you want to call the theme?', Component::defaultTheme());
        $path = resource_path('views/livewire/forms/' . $theme);

        if (! File::exists($path) || $this->confirm("A theme with the name <comment>$theme</comment> already exists. Do you want to overwrite it?")) {
            File::copyDirectory(__DIR__ . '/../../resources/views/', $path);
            $this->line("<info>[✓]</info> The theme was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path() . '/', '', $path);
    }
}
