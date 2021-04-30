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
        File::ensureDirectoryExists(resource_path('views/livewire'));

        $path = resource_path('views/livewire/' . Str::slug($this->form->handle()) . '.blade.php');

        if (!File::exists($path) || $this->confirm("A view for this form already exists. Do you want to overwrite it?")) {
            $stub = File::get(__DIR__ . '/../../resources/stubs/form.blade.php');
            File::put($path, $stub);
            $this->line("<info>[✓]</info> The view was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path() . '/', '', $path);
    }
}
