<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Currency extends Field
{
    use WithAutocomplete;
    use WithPlaceholder;

    protected string $view = 'currency';

    protected function inputTypeProperty(): string
    {
        return 'number';
    }

    protected function stepProperty(): mixed
    {
        return $this->field->get('step', 'any');
    }

    protected function currencyProperty(): ?string
    {
        return $this->currencyCode($this->field->formField()?->config()['currency'] ?? null);
    }

    protected function currencyCode(mixed $currency): ?string
    {
        if (is_array($currency)) {
            $currency = $currency[0] ?? null;
        }

        return filled($currency) ? (string) $currency : null;
    }

    protected function currencyPlaceholderProperty(): string
    {
        if (filled($this->placeholder)) {
            return $this->placeholder;
        }

        $step = $this->step;
        $decimals = 2;

        if (is_numeric($step) && (float) $step > 0) {
            $normalized = rtrim(rtrim(number_format((float) $step, 10, '.', ''), '0'), '.');
            $decimals = str_contains($normalized, '.') ? strlen(substr(strrchr($normalized, '.'), 1)) : 0;
        }

        return number_format(0, $decimals, '.', '');
    }
}
