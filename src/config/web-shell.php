<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WShell Enabled
    |--------------------------------------------------------------------------
    |
    | This option controls whether WShell is enabled or not.
    |
    */

    'enabled' => env('WEB_SHELL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the route that will be used to access WShell.
    | The 'as' attribute is essential for the package to function correctly.
    |
    */

    'route' => [
        'prefix' => 'web-shell',
        'as' => 'web-shell.',
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
