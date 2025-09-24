<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\Modules\GoogleAds\Repositories\GoogleAdsRepository::class, function ($app) {
            return new \Modules\GoogleAds\Repositories\GoogleAdsRepository();
        });

        $this->app->bind(\Modules\GoogleAds\Services\GoogleAdsService::class, function ($app) {
            return new \Modules\GoogleAds\Services\GoogleAdsService(
                $app->make(\Modules\GoogleAds\Repositories\GoogleAdsRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
