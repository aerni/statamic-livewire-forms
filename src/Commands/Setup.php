<?php

namespace Aerni\LivewireForms\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;

use function Laravel\Prompts\confirm;

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
        $this->call('livewire-forms:view');
    }

    protected function makeTheme(): void
    {
        $this->call('livewire-forms:theme');
    }

    protected function makeComponent(): void
    {
        if (confirm(label: 'Do you want to create a Livewire component to customize the behaviour of the form?')) {
            $this->call('livewire-forms:component');
        }
    }
}
