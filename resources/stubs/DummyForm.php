<?php

namespace App\Livewire;

use Aerni\LivewireForms\Form\Fields;
use Aerni\LivewireForms\Livewire\Concerns\WithStatamicFormBuilder;
use Livewire\Component;
use Statamic\Contracts\Forms\Submission;

class DummyForm extends Component
{
    use WithStatamicFormBuilder;

    /*
    |--------------------------------------------------------------------------
    | Initializing Properties
    |--------------------------------------------------------------------------
    |
    | You may assign the public properties in the mount instead of passing them
    | to the component in the view.
    |
    */

    // public function mount(): void
    // {
    //     $this->handle = 'contact';
    //     $this->theme = 'secondary';
    //     $this->view = 'default';
    // }

    /*
    |--------------------------------------------------------------------------
    | Field Models
    |--------------------------------------------------------------------------
    |
    | You may add unique models that only apply to this form component.
    | Use a field's handle as the key to only use the model for that particular field.
    |
    */

    // protected array $models = [
    //     \Statamic\Fieldtypes\Select::class => \App\Fields\Select::class,
    //     'product' => \App\Fields\SelectProduct::class,
    // ];

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Hooks
    |--------------------------------------------------------------------------
    |
    | You may use the following lifecycle hooks to modify fields and data
    | at various steps in the component lifecycle.
    |
    */

    // public function mountedFields($fields): void
    // {
    //     $fields->get('name')->label('Your name');
    // }

    // public function hydrateFields($fields): void
    // {
    //     $fields->get('name')->label('Your name');
    // }

    // public function formSubmitted(Submission $submission): void
    // {
    //     $submission->set('created_at', now()->timestamp);

    //     Newsletter::subscribe($submission->get('email'));
    // }

    /*
    |--------------------------------------------------------------------------
    | Messages and Submit Button Label
    |--------------------------------------------------------------------------
    |
    | You may override the default success and error messages
    | as well as the label of the submit button.
    |
    */

    // public function successMessage(): string
    // {
    //     return __("Thank you, {$this->submission->get('name')}!");
    // }

    // public function errorMessage(): string
    // {
    //     return __('Something went terribly wrong!');
    // }

    // public function submitButtonLabel(): string
    // {
    //     return __("Send now");
    // }
}
