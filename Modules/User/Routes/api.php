<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([\App\Http\Middleware\SanitizeInput::class, 'auth:api'])
    ->group(function () {
        Route::apiResource('users', UserController::class);
        Route::controller(UserController::class)->prefix('/users')->group(function () {
            Route::post('/reset/password/{id}', 'resetPassword');
        });
        Route::controller(UserLoginController::class)->prefix('/auth')->group(function () {
            Route::post('/logout', 'logout');
            Route::get('/current_user', 'getCurrentUser');
        });
        
    });
    Route::controller(UserLoginController::class)->prefix('/auth')->group(function () {
        Route::post('/login', 'login');
    });