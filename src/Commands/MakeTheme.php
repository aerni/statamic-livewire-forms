<?php

namespace Aerni\LivewireForms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class MakeTheme extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:theme {name?}';

    protected $description = 'Create a new Livewire Forms theme';

    public function handle(): void
    {
        $name = $this->argument('name') ?? text(label: 'What do you want to name the theme?', required: true);
        $path = resource_path('views/'.config('livewire-forms.view_path').'/'.snake_case($name));

        if (! File::exists($path) || confirm(label: 'A theme with this name already exists. Do you want to overwrite it?', default: false)) {
            File::copyDirectory(__DIR__.'/../../resources/views/default/', $path);
            info("The theme was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
