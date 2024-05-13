<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Form\Section;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Statamic\Fields\Section as FormSection;

trait WithSections
{
    #[Computed]
    public function sections(): Collection
    {
        return $this->formSections->map(function (FormSection $section, int $index) {
            return new Section(
                number: $index + 1,
                fields: $section->fields()->all()->keys()->all(),
                display: $section->display(),
                instructions: $section->instructions(),
            );
        });
    }

    public function section(string $handle): ?Section
    {
        return $this->sections->firstWhere(fn (Section $section) => $section->handle() === $handle);
    }
}
