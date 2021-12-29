<?php

namespace Aerni\LivewireForms\Form;

use Illuminate\Support\Arr;

class Field
{
    public function __construct(protected array $config)
    {
        //
    }

    public function __get($name): mixed
    {
        return Arr::get($this->config, $name);
    }
}
