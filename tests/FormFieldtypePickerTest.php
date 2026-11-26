<?php

use Aerni\LivewireForms\Fields\Captcha as CaptchaField;
use Aerni\LivewireForms\Fields\Hidden as HiddenField;
use Aerni\LivewireForms\Fields\Text;
use Aerni\LivewireForms\Fieldtypes\Captcha as CaptchaFieldtype;
use Aerni\LivewireForms\Fieldtypes\Forms\Captcha as CaptchaFormFieldtype;
use Aerni\LivewireForms\Fieldtypes\Forms\Hidden as HiddenFormFieldtype;
use Aerni\LivewireForms\Livewire\BaseForm;
use Aerni\LivewireForms\ServiceProvider;
use Facades\Statamic\Fields\FieldtypeRepository;
use Facades\Statamic\Forms\Fields\FormFieldtypeRepository;
use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;
use Statamic\Facades\Form as StatamicForm;
use Statamic\Forms\Fields\FormField;
use Statamic\Http\Controllers\CP\Forms\ManagesFormFields;

it('describes captcha and hidden in the field picker', function () {
    $picker = new class
    {
        use ManagesFormFields;

        public function types()
        {
            return invade($this)->fieldtypes();
        }
    };

    $types = $picker->types();

    $captcha = $types->filter(fn ($fieldtype) => $fieldtype->toArray()['handle'] === 'captcha');
    $hidden = $types->filter(fn ($fieldtype) => $fieldtype->toArray()['handle'] === 'hidden');

    expect($captcha)->toHaveCount(1)
        ->and($captcha->first()->toArray()['title'])->toBe('Captcha')
        ->and($captcha->first()->toArray()['description'])->toBe('A captcha to protect your form from spam.')
        ->and($captcha->first()->toArray()['icon'])->toBe('security-lock')
        ->and($hidden)->toHaveCount(1)
        ->and($hidden->first()->toArray()['title'])->toBe('Hidden')
        ->and($hidden->first()->toArray()['description'])->toBe('Stores a value without showing it, such as the user\'s locale.')
        ->and($hidden->first()->toArray()['icon'])->toBe('fieldtype-hidden');
});

it('keeps captcha and hidden as their original field types', function () {
    $captcha = FormFieldtypeRepository::find('captcha')->setField(new FormField('challenge', [
        'type' => 'captcha',
        'display' => 'Captcha',
        'validate' => 'required',
    ]));

    $hidden = FormFieldtypeRepository::find('hidden')->setField(new FormField('source', [
        'type' => 'hidden',
        'display' => 'Source',
        'default' => 'newsletter',
    ]));

    $fieldtype = FieldtypeRepository::find('captcha');

    expect($captcha->toFieldArray()['type'])->toBe('captcha')
        ->and($captcha->toFieldArray()['validate'])->toBe('required')
        ->and($fieldtype)->toBeInstanceOf(CaptchaFieldtype::class)
        ->and($fieldtype->selectable())->toBeFalse()
        ->and($fieldtype->selectableInForms())->toBeFalse()
        ->and($hidden->toFieldArray()['type'])->toBe('hidden')
        ->and($hidden->toFieldArray()['default'])->toBe('newsletter');
});

it('does not apply a model configured on the old fieldtype to the form field', function () {
    $provider = app()->getProvider(ServiceProvider::class);
    $published = [
        CaptchaFieldtype::class => Text::class,
        \Statamic\Fieldtypes\Hidden::class => Text::class,
    ];

    config(['livewire-forms.models' => $published]);
    invade($provider)->bootFieldModels();

    expect(config('livewire-forms.models')[CaptchaFormFieldtype::class])->toBe(CaptchaField::class)
        ->and(config('livewire-forms.models')[CaptchaFieldtype::class])->toBe(Text::class)
        ->and(config('livewire-forms.models')[HiddenFormFieldtype::class])->toBe(HiddenField::class)
        ->and(config('livewire-forms.models')[\Statamic\Fieldtypes\Hidden::class])->toBe(Text::class);

    config(['livewire-forms.models' => data_get(require __DIR__.'/../config/livewire-forms.php', 'models')]);
    invade($provider)->bootFieldModels();
});

it('renders captcha from the form field and leaves the response out of the submission', function () {
    config(['livewire-forms.captcha.key' => 'site-key']);
    Validator::extend('captcha', fn () => true);

    StatamicForm::make('gated')->formFields([
        'fields' => [
            ['handle' => 'name', 'field' => ['type' => 'short_answer']],
            ['handle' => 'challenge', 'field' => ['type' => 'captcha', 'display' => 'Captcha']],
            ['handle' => 'details', 'field' => ['type' => 'group', 'fields' => [
                ['handle' => 'nested', 'field' => ['type' => 'captcha', 'display' => 'Nested']],
            ]]],
        ],
    ])->honeypot('winnie')->save();

    Livewire::test(BaseForm::class, ['handle' => 'gated'])
        ->tap(fn ($component) => expect($component->instance()->fields->get('challenge'))->toBeInstanceOf(CaptchaField::class))
        ->set('submittableFields', [
            'name' => true,
            'challenge' => true,
            'details' => ['nested' => true],
            'winnie' => true,
        ])
        ->set('fields.name.value', 'Ada')
        ->set('fields.challenge.value', 'token')
        ->set('fields.details.value.nested', 'nested-token')
        ->call('submit')
        ->assertSuccessful();

    $submission = StatamicForm::find('gated')->submissions()->first();

    expect($submission->get('name'))->toBe('Ada')
        ->and($submission->get('challenge'))->toBeNull()
        ->and($submission->get('details'))->toEqual([]);

    StatamicForm::find('gated')->delete();
});
