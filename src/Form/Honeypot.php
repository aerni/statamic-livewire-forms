<?php

namespace Aerni\LivewireForms\Form;

use Aerni\LivewireForms\Traits\WithConfig;
use Illuminate\Support\Str;

class Honeypot
{
    use WithConfig;

    public function __construct(string $handle, string $id)
    {
        $this->config([
            'handle' => $handle,
            'id' => "{$id}_{$handle}",
            'key' => "data.{$handle}",
            'label' => Str::ucfirst($handle),
        ]);
    }

    public static function make(string $handle, string $id): self
    {
        return new static($handle, $id);
    }
}
