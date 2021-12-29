<?php

namespace Aerni\LivewireForms\Form;

use Illuminate\Support\Str;
use Aerni\LivewireForms\Form\Field;

class Honeypot
{
    public static function make(string $handle, string $id): Field
    {
        return new Field([
            'handle' => $handle,
            'label' => Str::ucfirst($handle),
            'id' => "{$id}_{$handle}",
            'key' => "data.{$handle}",
        ]);
    }
}
