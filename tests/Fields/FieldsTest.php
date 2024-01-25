<?php

use Aerni\LivewireForms\Exceptions\ReadOnlyPropertyException;
use Aerni\LivewireForms\Fields\Text;
use Livewire\Livewire;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Form as StatamicForm;

beforeEach(function () {
    Blueprint::makeFromFields([
        'name' => ['type' => 'text', 'display' => 'Name', 'validate' => 'required', 'some_custom_config' => 'value'],
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

it('can process the value', function () {
    $this->field->value('Michael');

    expect($this->field->process())->toBe('Michael');
});

it('can get the validation attributes', function () {
    expect($this->field->validationAttributes())->toBe(['fields.name.value' => 'Name']);
});

it('can get properties', function () {
    expect($this->field->properties())->not->toBeEmpty();
});

it('can set properties', function () {
    $this->field->properties(['foo' => 'bar']);

    expect(invade($this->field)->properties)->toEqual(['foo' => 'bar']);
});

it('can set a property', function () {
    expect(method_exists($this->field, 'fooProperty'))->toBeFalse();

    $this->field->foo('bar');

    expect($this->field->foo())->toBe('bar');
});

it('can set a property to null', function () {
    $this->field->properties();

    expect(invade($this->field)->properties)->toMatchArray(['display' => 'Name']);

    $this->field->display(null);

    expect(invade($this->field)->properties)->toMatchArray(['display' => null]);
});

it('can unset a property', function () {
    $this->field->properties();

    $this->field->unset('display');

    expect(invade($this->field)->properties)->not->toHaveKey('display');
});

it('processes properties through property methods', function () {
    expect(method_exists($this->field, 'rulesProperty'))->toBeTrue();

    expect($this->field->rules())->toEqual(['fields.name.value' => ['required']]);

    $this->field->rules('required|email');

    expect($this->field->rules())->toEqual(['fields.name.value' => ['required', 'email']]);
});

it('throws an exception when trying to set a read-only property', function () {
    expect(fn () => $this->field->id('foo'))->toThrow(ReadOnlyPropertyException::class);
});

it('gets properties from the field config', function () {
    expect(method_exists($this->field, 'someCustomConfigProperty'))->toBeFalse();

    expect($this->field->someCustomConfig())->toBe('value');
});

it('caches a property when getting its value', function () {
    $handle = $this->field->handle();
    $display = $this->field->display;
    $custom = $this->field->someCustomConfig;

    expect($handle)->toBe('name');
    expect($display)->toBe('Name');
    expect($custom)->toBe('value');

    expect(invade($this->field)->properties)->toEqual(['handle' => 'name', 'display' => 'Name', 'some_custom_config' => 'value']);
});

it('can get an array of the field', function () {
    $array = [
        'handle' => invade($this->field)->field->handle(),
        'config' => invade($this->field)->field->config(),
        'properties' => $this->field->properties(),
        'value' => $this->field->value(),
    ];

    expect($array)->toEqual($this->field->toArray());
});
