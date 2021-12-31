<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | You may change the implementation of each fieldtype with your own.
    |
    */

    'models' => [
        \Aerni\LivewireForms\Fieldtypes\Captcha::class => \Aerni\LivewireForms\Fields\Captcha::class,
        \Statamic\Fieldtypes\Checkboxes::class => \Aerni\LivewireForms\Fields\Checkbox::class,
        \Statamic\Fieldtypes\Integer::class => \Aerni\LivewireForms\Fields\Input::class,
        \Statamic\Fieldtypes\Radio::class => \Aerni\LivewireForms\Fields\Radio::class,
        \Statamic\Fieldtypes\Select::class => \Aerni\LivewireForms\Fields\Select::class,
        \Statamic\Fieldtypes\Text::class => \Aerni\LivewireForms\Fields\Input::class,
        \Statamic\Fieldtypes\Textarea::class => \Aerni\LivewireForms\Fields\Textarea::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Realtime Validation
    |--------------------------------------------------------------------------
    |
    | A boolean to globally enable/disable realtime validation.
    |
    */

    'realtime' => true,

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
        'secret' => env('CAPTCHA_SECRET')
    ],

];
