<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CountryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
// Authentication routes are already set up by Breeze in routes/auth.php

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Country Routes
    Route::apiResource('countries', CountryController::class);
    
    // Flag Management Routes
    Route::post('/countries/{country}/flag', [CountryController::class, 'uploadFlag']);
    Route::get('/countries/{country}/flag', [CountryController::class, 'getFlag']);
});