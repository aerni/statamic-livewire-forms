<?php

namespace Aerni\LivewireForms\Commands;

use Aerni\LivewireForms\Facades\Component;
use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;

class Setup extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:setup';
    protected $description = 'Setup your first Livewire form.';

    public function handle(): void
    {
        $this->makeView();
        $this->makeTheme();
        $this->makeComponent();
    }

    protected function makeView(): void
    {
        if ($this->confirm('Do you want to create a new form view?')) {
            $view = $this->ask('What do you want to call the view?', Component::defaultView());

            $this->call('livewire-forms:view', ['view' => $view]);
        }
    }

    protected function makeTheme(): void
    {
        if ($this->confirm('Do you want to create a new form theme?')) {
            $theme = $this->ask('What do you want to call the theme?', Component::defaultTheme());

            $this->call('livewire-forms:theme', ['theme' => $theme]);
        }
    }

    protected function makeComponent(): void
    {
        if ($this->confirm('Do you want to create a new form component to customize the default behaviour?')) {
            $name = $this->ask('What do you want to call the component?');

            $this->call('livewire-forms:component', ['name' => $name]);
        }
    }
}
