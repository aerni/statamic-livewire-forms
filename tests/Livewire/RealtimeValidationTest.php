<?php

use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Livewire\BaseForm;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Form as StatamicForm;
use Statamic\Fieldtypes\Grid;

class NestedItemsField extends Field
{
    protected string $view = 'default';

    protected function rulesProperty(string|array|null $rules = null): array
    {
        $rules = Arr::wrap(Arr::first(parent::rulesProperty($rules)));

        return [
            $this->key => [...$rules, 'array'],
            "{$this->key}.*.amount" => 'required|integer|min:1',
        ];
    }

    public function validationAttributes(): array
    {
        return [
            $this->key => 'Items',
            "{$this->key}.*.amount" => 'Amount',
        ];
    }
}

class NestedItemsForm extends BaseForm
{
    protected array $models = [
        'items' => NestedItemsField::class,
    ];
}

beforeEach(function () {
    Grid::makeSelectableInForms();

    Blueprint::makeFromFields([
        'services' => [
            'type' => 'checkboxes',
            'display' => 'Services',
            'options' => [
                'design' => 'Design',
                'dev' => 'Development',
            ],
            'validate' => 'required',
        ],
        'items' => [
            'type' => 'grid',
            'display' => 'Items',
            'fields' => [
                [
                    'handle' => 'amount',
                    'field' => [
                        'type' => 'integer',
                        'display' => 'Amount',
                    ],
                ],
            ],
            'validate' => 'required',
        ],
    ])->setHandle('order')->setNamespace('forms')->save();

    StatamicForm::make('order')
        ->honeypot('winnie')
        ->save();
});

afterEach(function () {
    Blueprint::find('forms.order')?->delete();
    StatamicForm::find('order')?->delete();
});

it('maps field update paths to the correct realtime validation key', function (string $updated, string $expected) {
    $form = Livewire::test(NestedItemsForm::class, ['handle' => 'order'])->instance();

    expect(invade($form)->realtimeValidationKey($updated))->toBe($expected);
})->with([
    'simple field' => ['name.value', 'fields.name.value'],
    'top-level checkbox item' => ['services.value.0', 'fields.services.value'],
    'grid nested property' => ['items.value.0.amount', 'fields.items.value.0.amount'],
    'checkbox inside grid row' => ['items.value.0.options.1', 'fields.items.value.0.options'],
    'deeper nested property' => ['items.value.0.meta.quantity', 'fields.items.value.0.meta.quantity'],
    'array item inside grid row' => ['items.value.0.items.2', 'fields.items.value.0.items'],
    'property under nested array item' => ['items.value.0.items.2.amount', 'fields.items.value.0.items.2.amount'],
]);

it('clears nested property validation errors when the value becomes valid', function () {
    $form = Livewire::test(NestedItemsForm::class, ['handle' => 'order'])
        ->set('submittableFields', [
            'services' => true,
            'items' => true,
            'winnie' => true,
        ])
        ->set('fields.items.value', [
            ['amount' => ''],
        ])
        ->call('submit')
        ->assertHasErrors(['fields.items.value.0.amount']);

    $form
        ->set('fields.items.value.0.amount', 1)
        ->assertHasNoErrors(['fields.items.value.0.amount']);
});

it('validates the checkbox array when a checkbox item is updated', function () {
    $form = Livewire::test(NestedItemsForm::class, ['handle' => 'order'])
        ->set('submittableFields', [
            'services' => true,
            'items' => true,
            'winnie' => true,
        ])
        ->set('fields.services.value', [])
        ->call('submit')
        ->assertHasErrors(['fields.services.value']);

    $form
        ->set('fields.services.value.0', 'design')
        ->assertHasNoErrors(['fields.services.value']);
});
