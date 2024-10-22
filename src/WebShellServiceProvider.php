<?php

namespace Maso\WebShell;

use Illuminate\Support\ServiceProvider;

class WebShellServiceProvider extends ServiceProvider
{

    /**
     * The current version of Web Shell.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishConfig();

        if (config('web-shell.enabled'))
        {
            $this->registerRoutes();
            $this->registerViews();
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {

        $this->mergeConfig();
    }

    /**
     * Register the package default config.
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/web-shell.php', 'web-shell');
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    /**
     * Register the package views.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'web-shell');
    }

    /**
     * Publish the package config.
     */
    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__ . '/config/web-shell.php' => config_path('web-shell.php'),
        ], 'config');
    }
}
