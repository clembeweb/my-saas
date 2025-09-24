<?php

use Illuminate\Support\Facades\Route;
use Modules\GoogleSearchConsole\Controllers\GoogleSearchConsoleController;
use Modules\GoogleSearchConsole\Controllers\SeoActivityController;

Route::prefix('api/v1/gsc')->middleware(['auth:sanctum'])->group(function () {
    // OAuth routes
    Route::get('/auth/url', [GoogleSearchConsoleController::class, 'getAuthUrl']);
    Route::post('/auth/callback', [GoogleSearchConsoleController::class, 'handleCallback']);

    // Properties routes
    Route::get('/properties', [GoogleSearchConsoleController::class, 'getProperties']);
    Route::get('/properties/{propertyId}/analytics', [GoogleSearchConsoleController::class, 'getSearchAnalytics']);
    Route::get('/properties/{propertyId}/export/csv', [GoogleSearchConsoleController::class, 'exportCsv']);
    Route::get('/properties/{propertyId}/areas', [GoogleSearchConsoleController::class, 'getAreas']);

    // Activities CRUD routes
    Route::get('/properties/{propertyId}/activities', [SeoActivityController::class, 'index']);
    Route::post('/properties/{propertyId}/activities', [SeoActivityController::class, 'store']);
    Route::get('/properties/{propertyId}/activities/{activityId}', [SeoActivityController::class, 'show']);
    Route::put('/properties/{propertyId}/activities/{activityId}', [SeoActivityController::class, 'update']);
    Route::delete('/properties/{propertyId}/activities/{activityId}', [SeoActivityController::class, 'destroy']);

    // Bulk operations
    Route::post('/properties/{propertyId}/activities/import', [SeoActivityController::class, 'bulkImport']);
    Route::get('/properties/{propertyId}/activities/export', [SeoActivityController::class, 'bulkExport']);

    // User preferences
    Route::get('/preferences', [GoogleSearchConsoleController::class, 'getUserPreferences']);
    Route::put('/preferences', [GoogleSearchConsoleController::class, 'updateUserPreferences']);
});

// Download route (no auth required for signed URLs)
Route::get('/gsc/download/{file}', function ($file) {
    $path = storage_path('app/exports/' . $file);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->download($path)->deleteFileAfterSend(true);
})->name('gsc.download');