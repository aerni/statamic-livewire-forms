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
        Statamic\Fieldtypes\Hidden::class => Aerni\LivewireForms\Fields\Hidden::class,
        Statamic\Fieldtypes\Integer::class => Aerni\LivewireForms\Fields\Integer::class,
        Statamic\Fieldtypes\Radio::class => Aerni\LivewireForms\Fields\Radio::class,
        Statamic\Fieldtypes\Select::class => Aerni\LivewireForms\Fields\Select::class,
        Statamic\Fieldtypes\Spacer::class => Aerni\LivewireForms\Fields\Spacer::class,
        Statamic\Fieldtypes\Text::class => Aerni\LivewireForms\Fields\Text::class,
        Statamic\Fieldtypes\Textarea::class => Aerni\LivewireForms\Fields\Textarea::class,
        Statamic\Fieldtypes\Toggle::class => Aerni\LivewireForms\Fields\Toggle::class,
        Statamic\Fieldtypes\Dictionary::class => Aerni\LivewireForms\Fields\Dictionary::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | The path under resources/views where the form views are published and loaded from.
    |
    */

    'view_path' => 'livewire/forms',

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
