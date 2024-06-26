<?php

namespace Aerni\LivewireForms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Features\SupportConsoleCommands\Commands\ComponentParser;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Form;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;

class MakeComponent extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:component';

    protected $description = 'Create a new Livewire Forms component';

    public function handle(): void
    {
        $forms = Form::all();

        if ($forms->isEmpty()) {
            error('There are no Statamic forms. You need at least one form to create a Livewire component.');

            return;
        }

        $name = select(
            label: 'Select the form for which you want to create a Livewire component.',
            options: $forms->mapWithKeys(fn ($form) => [$form->handle() => $form->title()]),
        );

        $classNamespace = config('livewire.class_namespace');
        $className = Str::of($name)->endsWith('Form') ? $name : Str::of($name)->append('Form')->studly();
        $classPath = ComponentParser::generatePathFromNamespace($classNamespace).collect()->push("{$className}.php")->implode('/');

        $stub = File::get(__DIR__.'/form.stub');

        $stub = preg_replace(
            ['/\[namespace\]/', '/\[class\]/'],
            [$classNamespace, $className],
            $stub
        );

        if (! File::exists($classPath) || confirm(label: 'A component with this name already exists. Do you want to overwrite it?', default: false)) {
            File::ensureDirectoryExists(dirname($classPath));
            File::put($classPath, $stub);
            info("The component was successfully created: <comment>{$this->getRelativePath($classPath)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
