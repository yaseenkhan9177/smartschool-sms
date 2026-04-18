<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\PwaSyncController;

// PWA Sync Routes (Using web middleware for session auth as it's the same domain)
Route::middleware(['web'])->group(function () {
    Route::post('/sync-attendance', [PwaSyncController::class, 'syncAttendance']);
    Route::post('/sync-fees', [PwaSyncController::class, 'syncFees']);
});
