<?php

namespace Modules\GoogleSearchConsole\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\GoogleSearchConsole\Services\GoogleSearchConsoleService;

class GoogleSearchConsoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(GoogleSearchConsoleService::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gsc');
    }
}