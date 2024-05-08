<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Livewire\DynamicForm;
use Aerni\LivewireForms\Livewire\WizardForm;
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
        if ($this instanceof DynamicForm) {
            return match ($this->type ?? null) {
                'wizard' => 'wizard',
                default => 'basic',
            };
        }

        return match (true) {
            ($this instanceof WizardForm) => 'wizard',
            default => 'basic',
        };
    }
}
