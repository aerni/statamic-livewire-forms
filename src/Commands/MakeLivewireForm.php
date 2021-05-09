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
        $this->publishFormViews();
        // $this->createComponent();
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
        $this->engine = $this->choice('Select your preferred templating engine', ['Antlers', 'Blade'], 0);
        $this->extension = ($this->engine === 'Antlers') ? '.antlers.html' : '.blade.php';

        $stub = File::get(__DIR__ . '/../../resources/stubs/form' . $this->extension);

        $filename = Str::slug($this->form->handle()) . $this->extension;
        $path = resource_path('views/livewire/' . $filename);

        if (!File::exists($path) || $this->confirm("The Livewire view <comment>$filename</comment> already exists. Overwrite?")) {
            File::ensureDirectoryExists(resource_path('views/livewire'));
            File::put($path, $stub);
            $this->line("<info>[✓]</info> The Livewire view was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    protected function publishFormViews(): void
    {
        if ($this->confirm('Do you want to publish the default form views?')) {
            $this->callSilently('vendor:publish', [
                '--tag' => 'livewire-forms-' . Str::lower($this->engine)
            ]);

            $path = resource_path('views/vendor/livewire-forms');

            $this->line("<info>[✓]</info> The default form views were successfully published: <comment>{$this->getRelativePath($path)}</comment>");
        }
    }

    // protected function createComponent(): void
    // {
    //     if ($this->confirm('Do you want to create a Livewire component to customize the defaut behaviour?')) {
    //         $stub = File::get(__DIR__ . '/../../resources/stubs/component.php');
    //         $stub = str_replace('DummyComponent', Str::studly($this->form->handle()), $stub);

    //         $path = app_path('Http/Livewire/' . Str::studly($this->form->handle()) . '.php');
    //         File::ensureDirectoryExists(app_path('Http/Livewire'));

    //         if (!File::exists($path) || $this->confirm("A component for this form already exists. Do you want to overwrite it?")) {
    //             File::put($path, $stub);
    //             $this->line("<info>[✓]</info> The Livewire component was successfully created: <comment>{$this->getRelativePath($path)}</comment>");
    //         }
    //     }
    // }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path() . '/', '', $path);
    }
}
