<?php

namespace Aerni\LivewireForms\Form;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Field;
use Illuminate\View\View as LaravelView;

class View
{
    public function field(Field $field, array $properties): LaravelView
    {
        foreach ($properties as $property => $value) {
            if ($property === 'view') {
                $field->view(Component::getView($value));
            } else {
                $field->$property($value);
            }
        }

        return view(Component::getView('field'), ['field' => $field]);
    }
}
