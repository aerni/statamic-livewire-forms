<?php

namespace App\Http\Livewire;

use Aerni\LivewireForms\Http\Livewire\Form;

class DummyForm extends Form
{
    /*
    |--------------------------------------------------------------------------
    | Initialize Properties
    |--------------------------------------------------------------------------
    |
    | You may specify the form's handle, view, and theme, so that you
    | don't have to pass them as tag parameters.
    |
    */

    // protected string $handle = 'contact';
    // protected string $view = 'default';
    // protected string $theme = 'default';

    /*
    |--------------------------------------------------------------------------
    | Field Models
    |--------------------------------------------------------------------------
    |
    | You may add unique models that only apply to this form component.
    | Use a field's handle as the key to only use the model for that field.
    |
    */

    // protected array $models = [
    //     \Statamic\Fieldtypes\Select::class => \App\Fields\Select::class,
    //     'product' => \App\Fields\SelectProduct::class,
    // ];

    /*
    |--------------------------------------------------------------------------
    | Callbacks
    |--------------------------------------------------------------------------
    |
    | You may use the following callback methods to hook into various
    | lifecycle steps to modify fields and data.
    |
    */

    // protected function hydratedFields(Fields $fields): void
    // {
    // }

    // protected function beforeSubmission(): void
    // {
    // }
}
