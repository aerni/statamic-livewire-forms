<?php

return [

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
    | ReCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | To configure correctly please visit https://developers.google.com/recaptcha/docs/start
    |
    */

    'captcha' => [
        // 'service' => 'Recaptcha', // options: Recaptcha / Hcaptcha
        'key' => env('CAPTCHA_KEY', ''),
        'secret' => env('CAPTCHA_SECRET', ''),
        // 'collections' => [],
        // 'forms' => [],
        // 'user_login' => false,
        // 'user_registration' => false,
        // 'error_message' => 'Captcha failed.',
        'disclaimer' => '',
        'invisible' => false,
        'hide_badge' => false,
        // 'enable_api_routes' => false,
    ],

];
