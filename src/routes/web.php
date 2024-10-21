<?php

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Maso\WShell\controllers\TerminalController;
use Maso\WShell\controllers\AssetController;
use Maso\WShell\http\Middleware\BindCSRFToken;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

/*
|--------------------------------------------------------------------------
| WShell Routes
|--------------------------------------------------------------------------
| These are the routes used by WShell.
|
*/

Route::group(config('wshell.route'), function ()
{
    Route::group(['middleware' => [StartSession::class]], function ()
    {

        Route::get('/', [TerminalController::class, 'index']);
        Route::get('/directory', [TerminalController::class, 'getWorkingDirectory'])->name('directory.show');
        Route::get('assets/{asset}', [AssetController::class, 'getAsset'])->name('asset.show');
        Route::post('/command', [TerminalController::class, 'runCommand'])->name('command');
    });
});
