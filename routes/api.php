<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\GoogleAds\Controllers\GoogleAdsController;
use App\Http\Controllers\Api\GoogleAdsSyncController;

// Google Ads API routes (for testing)
Route::prefix('v1/google')->group(function () {
    Route::post('/config', [GoogleAdsController::class, 'saveConfig']);
    Route::get('/auth/url', [GoogleAdsController::class, 'getAuthUrl']);
    Route::get('/auth/callback', [GoogleAdsController::class, 'handleCallback']);
    Route::get('/accounts', [GoogleAdsController::class, 'getAccounts']);
    Route::get('/campaigns', [GoogleAdsController::class, 'getCampaigns']);
    Route::get('/export/csv', [GoogleAdsController::class, 'exportCsv']);
});

// Google Ads Script Sync API routes (no authentication required for scripts)
Route::prefix('v1/sync')->group(function () {
    Route::post('/campaigns', [GoogleAdsSyncController::class, 'receiveCampaignData']);
    Route::get('/status', [GoogleAdsSyncController::class, 'getSyncStatus']);
});