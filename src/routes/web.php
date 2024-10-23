<?php

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Maso\LaravelTerminal\http\Controllers\TerminalController;
use Maso\LaravelTerminal\http\Controllers\AssetController;

/*
|--------------------------------------------------------------------------
| WShell Routes
|--------------------------------------------------------------------------
| These are the routes used by WShell.
|
*/

Route::group(config('laravel-terminal.route'), function ()
{
    Route::group(['middleware' => [StartSession::class]], function ()
    {

        Route::get('/', [TerminalController::class, 'index']);
        Route::get('/directory', [TerminalController::class, 'getWorkingDirectory'])->name('directory.show');
        Route::get('assets/{asset}', [AssetController::class, 'getAsset'])->name('asset.show');
        Route::post('/command', [TerminalController::class, 'handleCommand'])->name('command');
    });
});
