<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Terminal Enabled
    |--------------------------------------------------------------------------
    |
    | This option controls whether Terminal is enabled or not.
    |
    */

    'enabled' => env('TERMINAL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the route that will be used to access Terminal.
    | The 'as' attribute is essential for the package to function correctly.
    |
    */

    'route' => [
        'prefix' => 'terminal',
        'as' => 'terminal.',
        'middleware' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may customize the terminal interface.
    |
    */

    'terminal' => [
        'showInteractiveWarning' => true,
        'prompt' => '$',
        'header' => [
            " <span>Application Environment: <span class='ansi-bright-green'>"  . config('app.env') . "</span></span>" .
                "<br><span>Laravel " . Illuminate\Foundation\Application::VERSION . " (PHP v" . PHP_VERSION . "</span>)"
        ]
    ]


];
