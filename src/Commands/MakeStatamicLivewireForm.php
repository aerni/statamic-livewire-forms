<?php

namespace Aerni\StatamicLivewireForms\Commands;

use Statamic\Console\RunsInPlease;
use Statamic\Facades\Form;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeStatamicLivewireForm extends Command
{
    use RunsInPlease;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:statamic-livewire-form';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Create Livewire views for your Statamic forms';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->chooseForm();
        $this->createView();
        $this->createComponent();
    }

    protected function chooseForm(): void
    {
        $forms = Form::all();

        $formTitles = $forms->map(function ($form) {
            return $form->title();
        })->toArray();

        $formChoice = $this->choice('Select a form to create a view for:', $formTitles, 0);

        $chosenForm = $forms->filter(function ($form) use ($formChoice) {
            return $form->title() === $formChoice;
        })->first();

        $this->form = $chosenForm;
    }

    protected function createView(): void
    {
        $engine = $this->choice('Select your prefered templating engine', ['Antlers', 'Blade'], 0);
        $extension = ($engine === 'Antlers') ? '.antlers.html' : '.blade.php';

        $stub = File::get(__DIR__ . '/../../resources/stubs/form' . $extension);

        $path = resource_path('views/livewire/' . Str::slug($this->form->handle()) . $extension);
        File::ensureDirectoryExists(resource_path('views/livewire'));

        if (!File::exists($path) || $this->confirm("A view for this form already exists. Do you want to overwrite it?")) {
            File::put($path, $stub);
            $this->line("<info>[✓]</info> The view was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function createComponent(): void
    {
        if ($this->confirm('Do you want to create a Livewire component to customize the defaut behaviour?')) {
            $stub = File::get(__DIR__ . '/../../resources/stubs/component.stub');
            $stub = str_replace('DummyComponent', Str::studly($this->form->handle()), $stub);

            $path = app_path('Http/Livewire/' . Str::studly($this->form->handle()) . '.php');
            File::ensureDirectoryExists(app_path('Http/Livewire'));

            if (!File::exists($path) || $this->confirm("A component for this form already exists. Do you want to overwrite it?")) {
                File::put($path, $stub);
                $this->line("<info>[✓]</info> The Livewire component was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
            }
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path() . '/', '', $path);
    }
}
