<?php

namespace App\Livewire;

use Aerni\LivewireForms\Form\Fields;
use Aerni\LivewireForms\Livewire\Concerns\WithStatamicFormBuilder;
use Livewire\Component;

class DummyForm extends Component
{
    use WithStatamicFormBuilder;

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
    | Callbacks & Hooks
    |--------------------------------------------------------------------------
    |
    | You may use the following callbacks and hooks to modify fields and data
    | at various lifecycle steps.
    |
    */

    // protected function hydratedFields(Fields $fields): void
    // {
    //     $fields->get('name')->label('Your name');
    // }

    // protected function submittingForm(): void
    // {
    //     $this->data['success'] = true;
    // }

    // protected function createdSubmission(Submission $submission): void
    // {
    //     $title = $submission->augmentedValue('entry')->value()->title;
    //
    //     $submission->set('entry_title', $title);
    // }

    // protected function submittedForm(): void
    // {
    //     Newsletter::subscribe($this->data['email']);
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
    //     return __("Thank you, {$this->data['name']}!");
    // }

    // public function errorMessage(): string
    // {
    // return __("Something went terribly wrong!");
    // }

    // public function submitButtonLabel(): string
    // {
    //     return __("Send now");
    // }
}
