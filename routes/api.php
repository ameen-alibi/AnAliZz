<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('api-key');


Route::post('/login', [AuthController::class, 'login'])
    ->middleware('api-key');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');