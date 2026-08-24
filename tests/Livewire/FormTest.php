<?php

use Aerni\LivewireForms\Livewire\BaseForm;
use Aerni\LivewireForms\Livewire\DynamicForm;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Statamic\Events\FormSubmitted;
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
    Livewire::test(BaseForm::class, ['handle' => 'contact'])
        ->assertStatus(200);
});

it('shows success and does not persist a submission when the honeypot is filled', function () {
    filledContactForm(['winnie' => 'spam'])
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    expect(session('_flash.new'))->not->toContain('submission');
    expect(StatamicForm::find('contact')->submissions())->toHaveCount(0);
});

it('flashes and persists a submission on successful submit', function () {
    filledContactForm()
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    expect(session('_flash.new'))->toContain('submission');
    expect(StatamicForm::find('contact')->submissions())->toHaveCount(1);
});

it('flashes the submission when FormSubmitted returns false without persisting it', function () {
    Event::listen(FormSubmitted::class, fn () => false);

    filledContactForm()
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    expect(session('_flash.new'))->toContain('submission');
    expect(StatamicForm::find('contact')->submissions())->toHaveCount(0);
});

function filledContactForm(array $values = []): \Livewire\Features\SupportTesting\Testable
{
    $component = Livewire::test(BaseForm::class, ['handle' => 'contact'])
        ->set('submittableFields', [
            'name' => true,
            'email' => true,
            'message' => true,
            'winnie' => true,
        ]);

    foreach ([
        'name' => 'Michael',
        'email' => 'michael@example.com',
        'message' => 'Hello',
        ...$values,
    ] as $handle => $value) {
        $component->set("fields.{$handle}.value", $value);
    }

    return $component;
}
