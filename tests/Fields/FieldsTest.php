<?php

use Livewire\Livewire;
use Statamic\Fields\Field;
use Statamic\Facades\Blueprint;
use Aerni\LivewireForms\Fields\Text;
use Aerni\LivewireForms\Livewire\Form;
use Statamic\Facades\Form as StatamicForm;
use Aerni\LivewireForms\Livewire\DynamicForm;

beforeEach(function () {
    Blueprint::makeFromFields([
        'name' => ['type' => 'text', 'display' => 'Name'],
        'email' => ['type' => 'text', 'display' => 'Email'],
        'message' => ['type' => 'textarea', 'display' => 'Message']
    ])->setHandle('contact')->setNamespace('forms')->save();

    StatamicForm::make('contact')->save();

    $this->component = Livewire::new('form');
    $this->component->fill(['handle' => 'contact', 'theme' => 'default', 'view' => 'default']);

    $this->field = Text::make(
        field: StatamicForm::find('contact')->fields()->get('name'),
        component: $this->component
    );
});

afterEach(function () {
    Blueprint::find('forms.contact')->delete();
    StatamicForm::find('contact')->delete();
});

it('can get the id', function () {
    expect($this->field->id)->toBe("{$this->component->getId()}-field-name");
});
