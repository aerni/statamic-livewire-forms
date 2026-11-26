<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;
use Aerni\LivewireForms\Facades\ViewManager;
use Aerni\LivewireForms\Livewire\BaseForm;
use Aerni\LivewireForms\Livewire\DynamicForm;
use Aerni\LivewireForms\Livewire\Synthesizers\FieldSynth;
use Aerni\LivewireForms\Livewire\Synthesizers\RuleSynth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;
use Statamic\Facades\Form;
use Statamic\Fields\Section;
use Statamic\Forms\Fieldtypes\Checkboxes;
use Statamic\Forms\Fieldtypes\Currency;
use Statamic\Forms\Fieldtypes\DatePicker;
use Statamic\Forms\Fieldtypes\Dictionary;
use Statamic\Forms\Fieldtypes\Dropdown;
use Statamic\Forms\Fieldtypes\Email;
use Statamic\Forms\Fieldtypes\Group;
use Statamic\Forms\Fieldtypes\ImageChoice;
use Statamic\Forms\Fieldtypes\LongAnswer;
use Statamic\Forms\Fieldtypes\MultiChoice;
use Statamic\Forms\Fieldtypes\Name;
use Statamic\Forms\Fieldtypes\Number;
use Statamic\Forms\Fieldtypes\OpinionScale;
use Statamic\Forms\Fieldtypes\Phone;
use Statamic\Forms\Fieldtypes\Ranking;
use Statamic\Forms\Fieldtypes\ShortAnswer;
use Statamic\Forms\Fieldtypes\StarRating;
use Statamic\Forms\Fieldtypes\TimePicker;
use Statamic\Forms\Fieldtypes\Toggle;
use Statamic\Forms\Fieldtypes\Upload;
use Statamic\Forms\Fieldtypes\Website;
use Statamic\Forms\Fieldtypes\YesNo;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\MakeComponent::class,
        Commands\MakeTheme::class,
        Commands\MakeView::class,
        Commands\Setup::class,
    ];

    protected $fieldtypes = [
        Fieldtypes\Captcha::class,
    ];

    protected $publishables = [
        __DIR__.'/../resources/dist/js' => 'js',
        __DIR__.'/../resources/dist/css' => 'css',
    ];

    public function register(): void
    {
        $this->registerSerializableClasses([
            Section::class,
        ]);
    }

    public function bootAddon()
    {
        $this
            ->bootFieldModels()
            ->bootBladeDirectives()
            ->bootValidators()
            ->bootLivewire()
            ->bootSelectableFieldtypes()
            ->bootFormConfigFields()
            ->bootFieldConfigFields();
    }

    protected function bootFieldModels(): self
    {
        $defaultModels = data_get(require (__DIR__.'/../config/livewire-forms.php'), 'models');

        $userModels = config('livewire-forms.models');

        config()->set('livewire-forms.models', array_merge($defaultModels, $userModels));

        return $this;
    }

    protected function bootBladeDirectives(): self
    {
        foreach (get_class_methods(BladeDirectives::class) as $method) {
            Blade::directive($method, [BladeDirectives::class, $method]);
        }

        return $this;
    }

    protected function bootValidators(): self
    {
        Validator::extend('captcha', function ($attribute, $value) {
            return Captcha::verifyResponse($value, request()->getClientIp());
        }, __('livewire-forms::messages.captcha_challenge'));

        return $this;
    }

    protected function bootLivewire(): self
    {
        Livewire::component('form', DynamicForm::class);
        Livewire::component('base-form', BaseForm::class);

        Livewire::propertySynthesizer(FieldSynth::class);
        Livewire::propertySynthesizer(RuleSynth::class);

        return $this;
    }

    protected function bootSelectableFieldtypes(): self
    {
        \Statamic\Fieldtypes\Hidden::makeSelectableInForms();

        return $this;
    }

    protected function bootFormConfigFields(): self
    {
        Form::appendConfigFields('*', __('Livewire Forms'), [
            'type' => [
                'type' => 'button_group',
                'display' => __('Type'),
                'instructions' => __('Choose the desired type for this form.'),
                'options' => [
                    'basic' => __('Basic'),
                    'wizard' => __('Wizard'),
                ],
                'default' => 'basic',
            ],
            'view' => [
                'type' => 'select',
                'display' => __('View'),
                'instructions' => __('Choose the view for this form.'),
                'options' => ViewManager::views(),
                'clearable' => true,
                'width' => 50,
            ],
            'theme' => [
                'type' => 'select',
                'display' => __('Theme'),
                'instructions' => __('Choose the theme for this form.'),
                'options' => ViewManager::themes(),
                'clearable' => true,
                'width' => 50,
            ],
            'redirect' => [
                'type' => 'link',
                'display' => __('Redirect URL'),
                'instructions' => __('The users will be redirected to this URL after the form was submitted.'),
            ],
        ]);

        return $this;
    }

    protected function bootFieldConfigFields(): self
    {
        /* Fieldtypes that show a label and help text get Hide Label and Help Text Position. */
        foreach ([
            Checkboxes::class,
            Currency::class,
            DatePicker::class,
            Dictionary::class,
            Dropdown::class,
            Email::class,
            Group::class,
            ImageChoice::class,
            LongAnswer::class,
            MultiChoice::class,
            Name::class,
            Number::class,
            OpinionScale::class,
            Phone::class,
            Ranking::class,
            ShortAnswer::class,
            StarRating::class,
            TimePicker::class,
            Toggle::class,
            Upload::class,
            Website::class,
            YesNo::class,
            Fieldtypes\Captcha::class,
        ] as $fieldtype) {
            $fieldtype::appendConfigFields([
                'hide_display' => [
                    'display' => __('Hide Label'),
                    'instructions' => __("Don't show the label on the form."),
                    'type' => 'toggle',
                    'default' => false,
                ],
                'instructions_position' => [
                    'display' => __('Help Text Position'),
                    'instructions' => __('statamic::messages.fields_instructions_position_instructions'),
                    'type' => 'button_group',
                    'options' => [
                        'above' => __('Above'),
                        'below' => __('Below'),
                    ],
                    'default' => 'above',
                ],
            ]);
        }

        return $this;
    }
}
