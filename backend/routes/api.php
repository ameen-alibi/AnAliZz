<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\RegisterController;

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/track', [TrackerController::class, 'track']);


Route::post('/track', [TrackerController::class, 'store'])->middleware('throttle:60,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/sites', [WebsiteController::class, 'index']);
    Route::post('/sites', [WebsiteController::class, 'store']);
    Route::get('/sites/{id}', [WebsiteController::class, 'show']);
    Route::get('/sites/{id}/stats', [WebsiteController::class, 'stats']);
});
