<?php

use Aerni\LivewireForms\Fields;
use Aerni\LivewireForms\Livewire\BaseForm;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Statamic\Facades\Fieldset;
use Statamic\Facades\Form as StatamicForm;
use Statamic\Forms\DeleteTemporaryFiles;

beforeEach(function () {
    surveyForm();
});

afterEach(function () {
    StatamicForm::find('survey')?->delete();
});

it('renders every fieldtype', function () {
    $component = Livewire::test(BaseForm::class, ['handle' => 'survey'])
        ->assertSuccessful()
        ->assertSee('autocomplete="name"', false)
        ->assertSee('autocomplete="email"', false)
        ->assertSee('autocomplete="tel"', false)
        ->assertSee('autocomplete="url"', false)
        ->assertSee('type="email"', false)
        ->assertSee('type="tel"', false)
        ->assertSee('type="url"', false)
        ->assertSee('min="0"', false)
        ->assertSee('max="120"', false)
        ->assertSee('placeholder="0.00"', false)
        ->assertSee('focus-within:border-indigo-500', false)
        ->assertSee('USD', false)
        ->assertSee('step="1"', false)
        ->assertSee('<strong>read</strong>', false)
        ->assertSee('bg-yellow-50', false)
        ->assertSee('$wire.get(', false)
        ->assertSee('Speed', false)
        ->assertSee('Quality', false);

    $fields = $component->instance()->fields;
    $rules = $fields->get('schedule')->rules['fields.schedule.value.starts_at'];

    expect($fields->except('honeypot')->map(fn ($field) => $field::class)->all())->toEqual([
        'name' => Fields\ShortAnswer::class,
        'about' => Fields\LongAnswer::class,
        'topic' => Fields\Dropdown::class,
        'color' => Fields\MultiChoice::class,
        'interests' => Fields\Checkboxes::class,
        'newsletter' => Fields\Toggle::class,
        'recommend' => Fields\YesNo::class,
        'birthday' => Fields\DatePicker::class,
        'alarm' => Fields\TimePicker::class,
        'age' => Fields\Number::class,
        'price' => Fields\Currency::class,
        'country' => Fields\Dictionary::class,
        'rating' => Fields\StarRating::class,
        'score' => Fields\OpinionScale::class,
        'priorities' => Fields\Ranking::class,
        'style' => Fields\ImageChoice::class,
        'resume' => Fields\Upload::class,
        'address' => Fields\Group::class,
        'schedule' => Fields\Group::class,
        'full_name' => Fields\Name::class,
        'email' => Fields\Email::class,
        'phone' => Fields\Phone::class,
        'website' => Fields\Website::class,
        'intro' => Fields\FormHeading::class,
        'terms' => Fields\Html::class,
        'notice' => Fields\Info::class,
        'gap' => Fields\Spacer::class,
    ])
        ->and($rules)->toContain('date_format:H:i')
        ->and(collect($rules)->every(fn ($rule) => is_string($rule)))->toBeTrue();
});

it('binds a currency field inside a group from the config', function () {
    StatamicForm::make('priced')->formFields([
        'fields' => [
            ['handle' => 'details', 'field' => [
                'type' => 'group',
                'display' => 'Details',
                'fields' => [
                    ['handle' => 'budget', 'field' => [
                        'type' => 'currency',
                        'display' => 'Currency',
                        'currency' => 'CHF',
                    ]],
                    ['handle' => 'costs', 'field' => [
                        'type' => 'group',
                        'display' => 'Costs',
                        'fields' => [
                            ['handle' => 'budget', 'field' => [
                                'type' => 'currency',
                                'display' => 'Nested currency',
                                'currency' => 'EUR',
                            ]],
                        ],
                    ]],
                ],
            ]],
        ],
    ])->save();

    $markup = preg_replace('/wire:snapshot="[^"]*"/', '', Livewire::test(BaseForm::class, ['handle' => 'priced'])
        ->call('$refresh')
        ->html());

    expect($markup)->toContain('details-budget-currency')
        ->and($markup)->toContain('details-costs-budget-currency')
        ->and(substr_count($markup, 'CHF'))->toBe(1)
        ->and(substr_count($markup, 'EUR'))->toBe(1);

    StatamicForm::find('priced')->delete();
});

it('omits a currency symbol that repeats the currency code', function () {
    StatamicForm::make('priced')->formFields([
        'fields' => [
            ['handle' => 'budget', 'field' => [
                'type' => 'currency',
                'display' => 'Currency',
                'currency' => 'CHF',
            ]],
        ],
    ])->save();

    $markup = preg_replace('/wire:snapshot="[^"]*"/', '', Livewire::test(BaseForm::class, ['handle' => 'priced'])->html());

    expect(substr_count($markup, 'CHF'))->toBe(1);

    StatamicForm::find('priced')->delete();
});

it('persists a submission with every fieldtype', function () {
    filledSurveyForm([
        'about' => 'Hello there',
        'topic' => 'support',
        'color' => 'blue',
        'interests' => ['tech', 'design'],
        'newsletter' => true,
        'recommend' => 'yes',
        'birthday' => '1990-04-24',
        'alarm' => '07:30:00',
        'age' => 32,
        'price' => 19.99,
        'country' => 'GBR',
        'rating' => 4,
        'score' => 7,
        'priorities' => ['quality', 'speed'],
        'style' => 'dark',
        'address' => ['street' => 'Milson Road', 'city' => 'London'],
        'full_name' => 'Michael',
        'email' => 'michael@example.com',
        'phone' => '5551234567',
        'website' => 'https://example.com',
    ])
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    $submission = StatamicForm::find('survey')->submissions()->first();

    expect($submission->get('name'))->toBe('Michael')
        ->and($submission->get('about'))->toBe('Hello there')
        ->and($submission->get('topic'))->toBe('support')
        ->and($submission->get('color'))->toBe('blue')
        ->and($submission->get('interests'))->toBe(['tech', 'design'])
        ->and($submission->get('newsletter'))->toBeTrue()
        ->and($submission->get('recommend'))->toBe('yes')
        ->and($submission->get('birthday'))->toBe('1990-04-24')
        ->and($submission->get('alarm'))->toBe('07:30:00')
        ->and($submission->get('age'))->toBe(32)
        ->and($submission->get('price'))->toBe(19.99)
        ->and($submission->get('country'))->toBe('GBR')
        ->and($submission->get('rating'))->toBe(4)
        ->and($submission->get('score'))->toBe(7)
        ->and($submission->get('priorities'))->toBe(['quality', 'speed'])
        ->and($submission->get('style'))->toBe('dark')
        ->and($submission->get('address'))->toBe(['street' => 'Milson Road', 'city' => 'London'])
        ->and($submission->get('schedule'))->toBe(['starts_at' => '09:15'])
        ->and($submission->get('full_name'))->toBe('Michael')
        ->and($submission->get('email'))->toBe('michael@example.com')
        ->and($submission->get('phone'))->toBe('5551234567')
        ->and($submission->get('website'))->toBe('https://example.com');
});

it('uploads files through the upload fieldtype', function () {
    /* Statamic deletes the temporary files of non-stored upload fields after the emails have been sent. */
    Bus::fake(DeleteTemporaryFiles::class);

    filledSurveyForm(['resume' => [UploadedFile::fake()->create('resume.pdf', 100)]])
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    $submission = StatamicForm::find('survey')->submissions()->first();

    expect($submission->get('resume'))->toBe(["{$submission->id()}/resume/resume.pdf"]);

    Storage::disk(config('statamic.system.file_uploads_disk', 'local'))
        ->assertExists("statamic/form-uploads/{$submission->id()}/resume/resume.pdf");
});

it('renders legacy fields imported from a fieldset', function () {
    Fieldset::make('legacy_inputs')->setContents([
        'title' => 'Legacy inputs',
        'fields' => [
            ['handle' => 'age', 'field' => ['type' => 'integer', 'display' => 'Age']],
            ['handle' => 'color', 'field' => ['type' => 'radio', 'display' => 'Color', 'options' => ['red' => 'Red', 'blue' => 'Blue']]],
            ['handle' => 'topic', 'field' => ['type' => 'select', 'display' => 'Topic', 'options' => ['support' => 'Support', 'sales' => 'Sales']]],
            ['handle' => 'about', 'field' => ['type' => 'textarea', 'display' => 'About']],
            ['handle' => 'note', 'field' => ['type' => 'text', 'display' => 'Note']],
        ],
    ])->save();

    StatamicForm::make('imported')->formFields([
        'fields' => [
            ['import' => 'legacy_inputs'],
        ],
    ])->save();

    $fields = Livewire::test(BaseForm::class, ['handle' => 'imported'])
        ->assertSuccessful()
        ->instance()->fields;

    expect($fields->except('honeypot')->map(fn ($field) => $field::class)->all())->toEqual([
        'age' => Fields\Number::class,
        'color' => Fields\MultiChoice::class,
        'topic' => Fields\Dropdown::class,
        'about' => Fields\LongAnswer::class,
        'note' => Fields\ShortAnswer::class,
    ]);

    StatamicForm::find('imported')?->delete();
    Fieldset::find('legacy_inputs')?->delete();
});
