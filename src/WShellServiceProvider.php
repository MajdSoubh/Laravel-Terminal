<?php

namespace Maso\WShell;

use Illuminate\Support\ServiceProvider;

class WShellServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Publish the package config file into Laravel config directoy.
        $this->publishes([
            __DIR__ . '/config/wshell.php' => config_path('wshell.php'),
        ]);

        if (config('wshell.enabled'))
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
        $this->mergeConfigFrom(__DIR__ . '/config/wshell.php', 'wshell');
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'wshell');
    }
}
