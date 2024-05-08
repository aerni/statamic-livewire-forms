<?php

use Livewire\Livewire;
use Statamic\Facades\Blueprint;
use Aerni\LivewireForms\Livewire\BaseForm;
use Statamic\Facades\Form as StatamicForm;
use Aerni\LivewireForms\Livewire\BasicForm;
use Aerni\LivewireForms\Livewire\WizardForm;
use Aerni\LivewireForms\Livewire\DynamicForm;

beforeEach(function () {
    Blueprint::makeFromFields([
        'name' => ['type' => 'text', 'display' => 'Name'],
        'email' => ['type' => 'text', 'display' => 'Email'],
        'message' => ['type' => 'textarea', 'display' => 'Message'],
    ])->setHandle('contact')->setNamespace('forms')->save();

    StatamicForm::make('contact')
        ->honeypot('winnie')
        ->save();
});

afterEach(function () {
    Blueprint::find('forms.contact')->delete();
    StatamicForm::find('contact')->delete();
});

it('renders dynamic-form successfully', function () {
    Livewire::test(DynamicForm::class, ['handle' => 'contact'])
        ->assertStatus(200);
});

it('renders basic-form successfully', function () {
    Livewire::test(BaseForm::class, ['handle' => 'contact'])
        ->assertStatus(200);
});
