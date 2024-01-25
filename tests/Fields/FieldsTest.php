<?php

use Aerni\LivewireForms\Fields\Text;
use Livewire\Livewire;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Form as StatamicForm;

beforeEach(function () {
    Blueprint::makeFromFields([
        'name' => ['type' => 'text'],
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

it('can set and get the value', function () {
    $this->field->value('Michael');

    expect($this->field->value())->toBe('Michael');
});

it('can reset the value', function () {
    $this->field->value('Michael');
    $this->field->resetValue();

    expect($this->field->value())->toBe(null);
});

it('processed a property through its method', function () {
    expect($this->field->id)->toBe("{$this->component->getId()}-field-name");
});

it('caches a property when it is called', function () {
    $this->field->handle();
    $this->field->default;

    expect(invade($this->field)->properties)->toEqual(['handle' => 'name', 'default' => null]);
});

it('can set and get an arbitary property', function () {
    $this->field->foo('value');
    $this->field->bar = 'value';

    expect($this->field->foo)->toBe('value');
    expect($this->field->bar())->toBe('value');
});

it('can set a property to null', function () {
    $this->field->foo('value');

    $this->field->foo(null);

    expect($this->field->foo)->toBeNull();
});

it('can unset a property', function () {
    $this->field->foo('value');

    $this->field->unset('foo');

    expect(array_keys($this->field->properties()))->not->toContain('foo');
});

it('can get all properties', function () {
    expect($this->field->properties())->not->toBeEmpty();
});
