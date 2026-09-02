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
        Aerni\LivewireForms\Fieldtypes\Captcha::class => Aerni\LivewireForms\Fields\Captcha::class,
        Statamic\Fieldtypes\Assets\Assets::class => Aerni\LivewireForms\Fields\Assets::class,
        Statamic\Fieldtypes\Checkboxes::class => Aerni\LivewireForms\Fields\Checkboxes::class,
        Statamic\Fieldtypes\Date::class => Aerni\LivewireForms\Fields\Date::class,
        Statamic\Fieldtypes\Dictionary::class => Aerni\LivewireForms\Fields\Dictionary::class,
        Statamic\Fieldtypes\Files::class => Aerni\LivewireForms\Fields\Files::class,
        Statamic\Fieldtypes\FormBanner::class => Aerni\LivewireForms\Fields\FormBanner::class,
        Statamic\Fieldtypes\FormHeading::class => Aerni\LivewireForms\Fields\FormHeading::class,
        Statamic\Fieldtypes\FormUpload::class => Aerni\LivewireForms\Fields\FormUpload::class,
        Statamic\Fieldtypes\Group::class => Aerni\LivewireForms\Fields\Group::class,
        Statamic\Fieldtypes\Hidden::class => Aerni\LivewireForms\Fields\Hidden::class,
        Statamic\Fieldtypes\Html::class => Aerni\LivewireForms\Fields\Html::class,
        Statamic\Fieldtypes\ImageChoice::class => Aerni\LivewireForms\Fields\ImageChoice::class,
        Statamic\Fieldtypes\Integer::class => Aerni\LivewireForms\Fields\Integer::class,
        Statamic\Fieldtypes\OpinionScale::class => Aerni\LivewireForms\Fields\OpinionScale::class,
        Statamic\Fieldtypes\Radio::class => Aerni\LivewireForms\Fields\Radio::class,
        Statamic\Fieldtypes\Ranking::class => Aerni\LivewireForms\Fields\Ranking::class,
        Statamic\Fieldtypes\Select::class => Aerni\LivewireForms\Fields\Select::class,
        Statamic\Fieldtypes\Spacer::class => Aerni\LivewireForms\Fields\Spacer::class,
        Statamic\Fieldtypes\StarRating::class => Aerni\LivewireForms\Fields\StarRating::class,
        Statamic\Fieldtypes\Text::class => Aerni\LivewireForms\Fields\Text::class,
        Statamic\Fieldtypes\Textarea::class => Aerni\LivewireForms\Fields\Textarea::class,
        Statamic\Fieldtypes\Time::class => Aerni\LivewireForms\Fields\Time::class,
        Statamic\Fieldtypes\Toggle::class => Aerni\LivewireForms\Fields\Toggle::class,
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
