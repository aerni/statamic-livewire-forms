<?php

use Aerni\LivewireForms\Livewire\BaseForm;
use Facades\Statamic\Forms\Fields\FormFieldtypeRepository;
use Livewire\Livewire;
use Statamic\Facades\Form as StatamicForm;

it('offers instructions position on form fields that render it', function () {
    $positions = collect([
        'short_answer',
        'email',
        'name',
        'phone',
        'website',
        'number',
        'currency',
        'date_picker',
        'time_picker',
        'toggle',
        'long_answer',
        'dropdown',
        'dictionary',
        'checkboxes',
        'multi_choice',
        'yes_no',
        'group',
        'ranking',
        'opinion_scale',
        'star_rating',
        'image_choice',
        'upload',
        'captcha',
    ])->mapWithKeys(fn (string $handle) => [
        $handle => FormFieldtypeRepository::find($handle)->configFields()->has('instructions_position'),
    ]);

    expect($positions->contains(false))->toBeFalse()
        ->and(FormFieldtypeRepository::find('short_answer')->configFields()->get('instructions_position')->get('display'))->toBe('Help Text Position')
        ->and(FormFieldtypeRepository::find('short_answer')->configFields()->get('instructions_position')->get('type'))->toBe('button_group')
        ->and(FormFieldtypeRepository::find('short_answer')->configFields()->get('instructions_position')->get('if'))->toBeNull()
        ->and(FormFieldtypeRepository::find('short_answer')->configFields()->get('instructions_position')->get('default'))->toBe('above');
});

it('offers hide label on form fields that render a label', function () {
    $fields = collect([
        'short_answer',
        'email',
        'name',
        'phone',
        'website',
        'number',
        'currency',
        'date_picker',
        'time_picker',
        'toggle',
        'long_answer',
        'dropdown',
        'dictionary',
        'checkboxes',
        'multi_choice',
        'yes_no',
        'group',
        'ranking',
        'opinion_scale',
        'star_rating',
        'image_choice',
        'upload',
        'captcha',
    ])->mapWithKeys(fn (string $handle) => [
        $handle => FormFieldtypeRepository::find($handle)->configFields()->has('hide_display'),
    ]);

    $toggle = FormFieldtypeRepository::find('short_answer')->configFields()->get('hide_display');

    expect($fields->contains(false))->toBeFalse()
        ->and($toggle->get('display'))->toBe('Hide Label')
        ->and($toggle->get('type'))->toBe('toggle')
        ->and($toggle->get('default'))->toBeFalse()
        ->and($toggle->get('if'))->toBeNull();
});

it('visually hides a label that is marked hidden', function () {
    StatamicForm::make('agreement')->formFields([
        'fields' => [
            ['handle' => 'name', 'field' => [
                'type' => 'short_answer',
                'display' => 'Your name',
                'hide_display' => true,
            ]],
        ],
    ])->save();

    $markup = preg_replace('/wire:snapshot="[^"]*"/', '', Livewire::test(BaseForm::class, ['handle' => 'agreement'])->html());

    expect($markup)->toContain('sr-only')
        ->and($markup)->toContain('Your name');

    StatamicForm::find('agreement')->delete();
});

it('leaves instructions position off form fields that do not render it', function () {
    $positions = collect([
        'spacer',
        'heading',
        'paragraph',
        'banner',
        'hidden',
    ])->mapWithKeys(fn (string $handle) => [
        $handle => FormFieldtypeRepository::find($handle)->configFields()->has('instructions_position'),
    ]);

    expect($positions->contains(true))->toBeFalse()
        ->and(collect(['spacer', 'heading', 'paragraph', 'banner', 'hidden'])->contains(
            fn (string $handle) => FormFieldtypeRepository::find($handle)->configFields()->has('hide_display')
        ))->toBeFalse()
        ->and(FormFieldtypeRepository::find('hidden')->configFields()->get('instructions')->get('type'))->toBe('hidden');
});

it('renders toggle help text in the chosen position', function () {
    StatamicForm::make('agreement')->formFields([
        'fields' => [
            ['handle' => 'agree', 'field' => [
                'type' => 'toggle',
                'display' => 'Agree',
                'inline_label' => 'I agree',
                'instructions' => 'Read this first.',
                'instructions_position' => 'below',
            ]],
        ],
    ])->save();

    $html = Livewire::test(BaseForm::class, ['handle' => 'agreement'])->html();
    $markup = preg_replace('/wire:snapshot="[^"]*"/', '', $html);

    expect(strpos($markup, 'Agree'))->toBeLessThan(strpos($markup, 'I agree'))
        ->and(strpos($markup, 'I agree'))->toBeLessThan(strpos($markup, 'Read this first.'));

    StatamicForm::find('agreement')->delete();

    StatamicForm::make('agreement')->formFields([
        'fields' => [
            ['handle' => 'agree', 'field' => [
                'type' => 'toggle',
                'display' => 'Agree',
            ]],
        ],
    ])->save();

    $markup = preg_replace('/wire:snapshot="[^"]*"/', '', Livewire::test(BaseForm::class, ['handle' => 'agreement'])->html());

    expect(substr_count($markup, 'Agree'))->toBe(1);

    StatamicForm::find('agreement')->delete();
});
