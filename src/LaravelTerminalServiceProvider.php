<?php

namespace Maso\LaravelTerminal;

use Illuminate\Support\ServiceProvider;

class LaravelTerminalServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishConfig();

        if (config('laravel-terminal.enabled'))
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
        $this->mergeConfigFrom(__DIR__ . '/config/laravel-terminal.php', 'laravel-terminal');
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'laravel-terminal');
    }

    /**
     * Publish the package config.
     */
    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__ . '/config/laravel-terminal.php' => config_path('laravel-terminal.php'),
        ], 'config');
    }
}
