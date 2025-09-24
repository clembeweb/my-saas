<?php

namespace Modules\GoogleAds\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\GoogleAds\Services\GoogleAdsService;
use Modules\GoogleAds\Repositories\GoogleAdsRepository;

class GoogleAdsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(GoogleAdsRepository::class, function ($app) {
            return new GoogleAdsRepository();
        });

        $this->app->bind(GoogleAdsService::class, function ($app) {
            return new GoogleAdsService($app->make(GoogleAdsRepository::class));
        });
    }

    public function boot()
    {
        //
    }
}