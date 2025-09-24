<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Google Ads Tool Routes
Route::middleware(['auth', 'verified'])->prefix('google-ads')->name('google-ads.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\GoogleAdsWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/connect', [App\Http\Controllers\GoogleAdsWebController::class, 'connect'])->name('connect');
    Route::get('/callback', [App\Http\Controllers\GoogleAdsWebController::class, 'callback'])->name('callback');
    Route::get('/campaigns', [App\Http\Controllers\GoogleAdsWebController::class, 'campaigns'])->name('campaigns');
    Route::post('/disconnect', [App\Http\Controllers\GoogleAdsWebController::class, 'disconnect'])->name('disconnect');

    // Script Management Routes
    Route::get('/script-manager', [App\Http\Controllers\GoogleAdsWebController::class, 'scriptManager'])->name('script-manager');
    Route::get('/generate-script', [App\Http\Controllers\GoogleAdsWebController::class, 'generateScript'])->name('generate-script');
    Route::get('/synced-campaigns', [App\Http\Controllers\GoogleAdsWebController::class, 'syncedCampaigns'])->name('synced-campaigns');
});

require __DIR__.'/auth.php';
