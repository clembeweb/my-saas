<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\GoogleAds\Controllers\GoogleAdsController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});

// Google Ads API routes with tenant context
Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api/v1/google')->group(function () {
    Route::post('/config', [GoogleAdsController::class, 'saveConfig']);
    Route::get('/auth/url', [GoogleAdsController::class, 'getAuthUrl']);
    Route::get('/auth/callback', [GoogleAdsController::class, 'handleCallback']);
    Route::get('/accounts', [GoogleAdsController::class, 'getAccounts']);
    Route::get('/campaigns', [GoogleAdsController::class, 'getCampaigns']);
    Route::get('/export/csv', [GoogleAdsController::class, 'exportCsv']);
});
