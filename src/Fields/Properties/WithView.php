<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithView
{
    abstract public function view(): string;

    public function viewProperty(): string
    {
        return "{$this->theme}{$this->view()}";
    }
}
