<?php

namespace Aerni\LivewireForms\Commands;

use Aerni\LivewireForms\Facades\Component;
use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;

class Setup extends Command
{
    use RunsInPlease;

    protected $signature = 'livewire-forms:setup';
    protected $description = 'Step by step wizard to get you started';

    public function handle(): void
    {
        $this->makeView();
        $this->makeTheme();
        $this->makeComponent();
    }

    protected function makeView(): void
    {
        if ($this->confirm('Do you want to create a new form view?')) {
            $this->call('livewire-forms:view');
        }
    }

    protected function makeTheme(): void
    {
        if ($this->confirm('Do you want to create a new form theme?')) {
            $this->call('livewire-forms:theme');
        }
    }

    protected function makeComponent(): void
    {
        if ($this->confirm('Do you want to create a new form component to customize the default behaviour?')) {
            $this->call('livewire-forms:component');
        }
    }
}
