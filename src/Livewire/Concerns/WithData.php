<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait WithData
{
    public array $data = [];

    public function mountWithData(): void
    {
        $this->data = $this->defaultData();
    }

    protected function defaultData(): array
    {
        return collect($this->data)
            ->only($this->fields->captcha()?->handle ?? []) // Make sure to preserve the captcha response.
            ->merge($this->fields->defaultValues())
            ->all();
    }
}
