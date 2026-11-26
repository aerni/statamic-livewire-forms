<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Field Models
    |--------------------------------------------------------------------------
    |
    | You may change the model of each fieldtype with your own implementation.
    |
    */

    'models' => [
        Aerni\LivewireForms\Fieldtypes\Forms\Captcha::class => Aerni\LivewireForms\Fields\Captcha::class,
        Aerni\LivewireForms\Fieldtypes\Forms\Hidden::class => Aerni\LivewireForms\Fields\Hidden::class,
        Statamic\Forms\Fieldtypes\Banner::class => Aerni\LivewireForms\Fields\Info::class,
        Statamic\Forms\Fieldtypes\Checkboxes::class => Aerni\LivewireForms\Fields\Checkboxes::class,
        Statamic\Forms\Fieldtypes\Currency::class => Aerni\LivewireForms\Fields\Currency::class,
        Statamic\Forms\Fieldtypes\DatePicker::class => Aerni\LivewireForms\Fields\DatePicker::class,
        Statamic\Forms\Fieldtypes\Dictionary::class => Aerni\LivewireForms\Fields\Dictionary::class,
        Statamic\Forms\Fieldtypes\Dropdown::class => Aerni\LivewireForms\Fields\Dropdown::class,
        Statamic\Forms\Fieldtypes\Email::class => Aerni\LivewireForms\Fields\Email::class,
        Statamic\Forms\Fieldtypes\Group::class => Aerni\LivewireForms\Fields\Group::class,
        Statamic\Forms\Fieldtypes\Heading::class => Aerni\LivewireForms\Fields\FormHeading::class,
        Statamic\Forms\Fieldtypes\ImageChoice::class => Aerni\LivewireForms\Fields\ImageChoice::class,
        Statamic\Forms\Fieldtypes\LongAnswer::class => Aerni\LivewireForms\Fields\LongAnswer::class,
        Statamic\Forms\Fieldtypes\MultiChoice::class => Aerni\LivewireForms\Fields\MultiChoice::class,
        Statamic\Forms\Fieldtypes\Name::class => Aerni\LivewireForms\Fields\Name::class,
        Statamic\Forms\Fieldtypes\Number::class => Aerni\LivewireForms\Fields\Number::class,
        Statamic\Forms\Fieldtypes\OpinionScale::class => Aerni\LivewireForms\Fields\OpinionScale::class,
        Statamic\Forms\Fieldtypes\Paragraph::class => Aerni\LivewireForms\Fields\Html::class,
        Statamic\Forms\Fieldtypes\Phone::class => Aerni\LivewireForms\Fields\Phone::class,
        Statamic\Forms\Fieldtypes\Ranking::class => Aerni\LivewireForms\Fields\Ranking::class,
        Statamic\Forms\Fieldtypes\ShortAnswer::class => Aerni\LivewireForms\Fields\ShortAnswer::class,
        Statamic\Forms\Fieldtypes\Spacer::class => Aerni\LivewireForms\Fields\Spacer::class,
        Statamic\Forms\Fieldtypes\StarRating::class => Aerni\LivewireForms\Fields\StarRating::class,
        Statamic\Forms\Fieldtypes\TimePicker::class => Aerni\LivewireForms\Fields\TimePicker::class,
        Statamic\Forms\Fieldtypes\Toggle::class => Aerni\LivewireForms\Fields\Toggle::class,
        Statamic\Forms\Fieldtypes\Upload::class => Aerni\LivewireForms\Fields\Upload::class,
        Statamic\Forms\Fieldtypes\Website::class => Aerni\LivewireForms\Fields\Website::class,
        Statamic\Forms\Fieldtypes\YesNo::class => Aerni\LivewireForms\Fields\YesNo::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | The subdirectory within Livewire's configured view path where form views
    | are published and loaded from. This is relative to the 'view_path'
    | setting in your Livewire config (default: resources/views/livewire).
    |
    */

    'view_path' => 'forms',

    /*
    |--------------------------------------------------------------------------
    | Default View
    |--------------------------------------------------------------------------
    |
    | The default view that is used if you don't specify one on the component.
    |
    */

    'view' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | The default theme that is used if you don't specify one on the component.
    |
    */

    'theme' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Captcha Configuration
    |--------------------------------------------------------------------------
    |
    | Add the credentials for your captcha.
    | This addon currently supports Google reCAPTCHA v2 (checkbox).
    |
    */

    'captcha' => [
        'key' => env('CAPTCHA_KEY'),
        'secret' => env('CAPTCHA_SECRET'),
    ],

];
