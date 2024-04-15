<?php

namespace Aerni\LivewireForms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Form;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;

class MakeComponent extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:component {name?}';

    protected $description = 'Create a new Livewire form component';

    public function handle(): void
    {
        $name = $this->argument('name') ?? select(
            label: 'Select the form for which you want to create a Livewire component.',
            options: Form::all()->mapWithKeys(fn ($form) => [$form->handle() => $form->title()]),
        );

        $filename = Str::of($name)->endsWith('Form') ? $name : Str::of($name)->append('Form')->studly();

        $stub = File::get(__DIR__.'/../../resources/stubs/DummyForm.php');
        $stub = str_replace('DummyForm', $filename, $stub);
        $path = app_path("Livewire/{$filename}.php");

        if (! File::exists($path) || confirm(label: 'A component with this name already exists. Do you want to overwrite it?', default: false)) {
            File::ensureDirectoryExists(app_path('Livewire'));
            File::put($path, $stub);
            info("The component was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
