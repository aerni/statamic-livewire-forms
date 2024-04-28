<?php

use Aerni\LivewireForms\Livewire\BasicForm;
use Aerni\LivewireForms\Livewire\DynamicForm;
use Aerni\LivewireForms\Livewire\WizardForm;
use Livewire\Livewire;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Form as StatamicForm;

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
    Livewire::test(BasicForm::class, ['handle' => 'contact'])
        ->assertStatus(200);
});

it('renders wizard-form successfully', function () {
    Livewire::test(WizardForm::class, ['handle' => 'contact'])
        ->assertStatus(200);
});
