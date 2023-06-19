<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
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

Route::get('/travel', [App\Http\Controllers\Api\V1\TravelController::class, 'index']);
Route::get('/travel/{travel:slug}/tours', [App\Http\Controllers\Api\V1\ToursController::class, 'index']);

Route::middleware([RoleMiddleware::class])->group(function () {
    Route::post('/travel/create', [App\Http\Controllers\Api\V1\TravelController::class, 'store']);
    Route::post('/travel/{travel}/tours/create', [App\Http\Controllers\Api\V1\ToursController::class, 'store']);
});

Route::post('login', App\Http\Controllers\Api\V1\Auth\LoginController::class)->name('login');
