<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Locked;

trait WithType
{
    #[Locked]
    public string $type;

    public function mountWithType(): void
    {
        $this->type = $this->type();
    }

    protected function type(): string
    {
        return match ($this->type ?? $this->form->type) {
            'wizard' => 'wizard',
            default => 'basic',
        };
    }

    protected function isWizardForm(): bool
    {
        return $this->type === 'wizard';
    }
}
