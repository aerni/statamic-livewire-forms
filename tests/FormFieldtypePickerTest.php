<?php

use Aerni\LivewireForms\Fields\Captcha as CaptchaField;
use Aerni\LivewireForms\Fields\Text;
use Aerni\LivewireForms\Fieldtypes\Captcha as CaptchaFieldtype;
use Aerni\LivewireForms\Fieldtypes\Forms\Captcha as CaptchaFormFieldtype;
use Aerni\LivewireForms\Livewire\BaseForm;
use Aerni\LivewireForms\ServiceProvider;
use Facades\Statamic\Fields\FieldtypeRepository;
use Facades\Statamic\Forms\Fields\FormFieldtypeRepository;
use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;
use Statamic\Facades\Form as StatamicForm;
use Statamic\Forms\Fields\FormField;
use Statamic\Http\Controllers\CP\Forms\ManagesFormFields;

it('describes captcha in the field picker', function () {
    $picker = new class
    {
        use ManagesFormFields;

        public function types()
        {
            return invade($this)->fieldtypes();
        }
    };

    $captcha = $picker->types()->filter(fn ($fieldtype) => $fieldtype->toArray()['handle'] === 'captcha');

    expect($captcha)->toHaveCount(1)
        ->and($captcha->first()->toArray()['title'])->toBe('Captcha')
        ->and($captcha->first()->toArray()['description'])->toBe('A captcha to protect your form from spam.')
        ->and($captcha->first()->toArray()['icon'])->toBe('security-lock');
});

it('keeps captcha as its original field type', function () {
    $captcha = FormFieldtypeRepository::find('captcha')->setField(new FormField('challenge', [
        'type' => 'captcha',
        'display' => 'Captcha',
        'validate' => 'required',
    ]));

    $fieldtype = FieldtypeRepository::find('captcha');

    expect($captcha->toFieldArray()['type'])->toBe('captcha')
        ->and($captcha->toFieldArray()['validate'])->toBe('required')
        ->and($fieldtype)->toBeInstanceOf(CaptchaFieldtype::class)
        ->and($fieldtype->selectable())->toBeFalse()
        ->and($fieldtype->selectableInForms())->toBeFalse();
});

it('does not apply a model configured on the blueprint captcha fieldtype', function () {
    $provider = app()->getProvider(ServiceProvider::class);

    config(['livewire-forms.models' => [
        CaptchaFieldtype::class => Text::class,
    ]]);
    invade($provider)->bootFieldModels();

    expect(config('livewire-forms.models')[CaptchaFormFieldtype::class])->toBe(CaptchaField::class)
        ->and(config('livewire-forms.models')[CaptchaFieldtype::class])->toBe(Text::class);

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
        ],
    ])->honeypot('winnie')->save();

    Livewire::test(BaseForm::class, ['handle' => 'gated'])
        ->tap(fn ($component) => expect($component->instance()->fields->get('challenge'))->toBeInstanceOf(CaptchaField::class))
        ->set('submittableFields', [
            'name' => true,
            'challenge' => true,
            'winnie' => true,
        ])
        ->set('fields.name.value', 'Ada')
        ->set('fields.challenge.value', 'token')
        ->call('submit')
        ->assertSuccessful();

    $submission = StatamicForm::find('gated')->submissions()->first();

    expect($submission->get('name'))->toBe('Ada')
        ->and($submission->get('challenge'))->toBeNull();

    StatamicForm::find('gated')->delete();
});
