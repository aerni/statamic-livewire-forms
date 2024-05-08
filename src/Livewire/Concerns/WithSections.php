<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Form\Section;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

trait WithSections
{
    #[Computed]
    public function sections(): Collection
    {
        return $this->formSections->map(function ($section, $index) {
            return new Section(
                number: $index + 1,
                fields: $this->fields->intersectByKeys($section->fields()->all()),
                display: $section->display(),
                instructions: $section->instructions(),
            );
        });
    }

    public function section(string $handle): ?Section
    {
        return $this->sections->firstWhere(fn ($section) => $section->handle() === $handle);
    }
}
