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
        'middleware' => ['web'],
    ],

];
