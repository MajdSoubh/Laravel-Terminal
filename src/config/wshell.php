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

    'enabled' => env('WSHELL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the route that will be used to access WShell.
    |
    */

    'route' => [
        'prefix' => 'wshell',
        'as' => 'wshell.',
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
        'cursorBlink' => true,
        'prompt' => '$ ',
        'header' => [
            'Welcome to <span class="ansi-blue" style="color: #61afef; font-weight:bold">Laravel Shell</span><span class="ansi-bright-yellow" style="color: #f3f99d;"> v' . \Jakyeru\LaravelShell\LaravelShellServiceProvider::VERSION . '</span>',
            'Running Laravel <span class="ansi-bright-yellow" style="color: #f3f99d;"> v' . Illuminate\Foundation\Application::VERSION . '</span> (PHP <span class="ansi-bright-yellow" style="color: #f3f99d;">v' . PHP_VERSION . '</span>)',

        ]
    ]


];
