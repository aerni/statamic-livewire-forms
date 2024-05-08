<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Exceptions\FormNotFoundException;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Statamic\Facades\Form;
use Statamic\Fields\Section;

trait WithForm
{
    #[Computed(true)]
    public function form(): \Statamic\Forms\Form
    {
        return Form::find($this->handle)
            ?? throw new FormNotFoundException($this->handle);
    }

    #[Computed(true)]
    protected function formSections(): Collection
    {
        return $this->form->blueprint()->tabs()->first()->sections()
            ->filter(fn (Section $section) => $section->fields()->all()->isNotEmpty())
            ->values();
    }
}
