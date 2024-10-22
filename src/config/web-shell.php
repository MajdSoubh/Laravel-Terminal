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
            '<span class="ansi-blue" style="color: #61afef; font-weight:bold">Laravel Web Shell</span><span class="ansi-bright-yellow" style="color: #f3f99d;"> v' . \Maso\WebShell\WebShellServiceProvider::VERSION . '</span>',
            'Running Laravel <span class="ansi-bright-yellow" style="color: #f3f99d;"> v' . Illuminate\Foundation\Application::VERSION . '</span> (PHP <span class="ansi-bright-yellow" style="color: #f3f99d;">v' . PHP_VERSION . '</span>)',

        ]
    ]


];
