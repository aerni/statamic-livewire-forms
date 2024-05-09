<?php

namespace Aerni\LivewireForms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Console\RunsInPlease;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class MakeView extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:view {name?}';

    protected $description = 'Create a new Livewire Forms view';

    public function handle(): void
    {
        $name = $this->argument('name') ?? text(label: 'What do you want to name the view?', required: true);
        $stub = File::get(__DIR__.'/../../resources/views/default.blade.php');
        $filename = Str::slug($name).'.blade.php';
        $path = resource_path('views/'.config('livewire-forms.view_path')."/{$filename}");

        if (! File::exists($path) || confirm(label: 'A view with this name already exists. Do you want to overwrite it?', default: false)) {
            File::ensureDirectoryExists(resource_path('views/'.config('livewire-forms.view_path')));
            File::put($path, $stub);
            info("The view was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
