<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithSection
{
    protected function sectionProperty(): string
    {
        return $this->field->get('section', 'undefined');
    }
}
