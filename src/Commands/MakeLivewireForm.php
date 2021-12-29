<?php

namespace Aerni\LivewireForms\Commands;

use Statamic\Facades\Form;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Illuminate\Support\Facades\File;

class MakeLivewireForm extends Command
{
    use RunsInPlease;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:livewire-form';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Create a new Livewire form view';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->chooseForm();
        $this->createLivewireView();
        $this->createComponent();
    }

    protected function chooseForm(): void
    {
        $forms = Form::all();

        $formTitles = $forms->map(function ($form) {
            return $form->title();
        })->toArray();

        $formChoice = $this->choice('Select a form to create a Livewire view for:', $formTitles, 0);

        $chosenForm = $forms->filter(function ($form) use ($formChoice) {
            return $form->title() === $formChoice;
        })->first();

        $this->form = $chosenForm;
    }

    protected function createLivewireView(): void
    {
        $stub = File::get(__DIR__ . '/../../resources/stubs/form.blade.php');

        $filename = Str::slug($this->form->handle()) . '.blade.php';
        $path = resource_path('views/livewire/forms/' . $filename);

        if (!File::exists($path) || $this->confirm("The Livewire view <comment>$filename</comment> already exists. Overwrite?")) {
            File::ensureDirectoryExists(resource_path('views/livewire/forms'));
            File::put($path, $stub);
            $this->line("<info>[✓]</info> The form view was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function createComponent(): void
    {
        if ($this->confirm('Would you like to create a Livewire component to customize the default Form component behaviour?')) {
            $stub = File::get(__DIR__ . '/../../resources/stubs/form.php');
            $stub = str_replace('DummyForm', Str::studly($this->form->handle()), $stub);

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
