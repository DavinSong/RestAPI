<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get(uri: '/login', action: AuthController::class)
    ->name(name: 'login');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
