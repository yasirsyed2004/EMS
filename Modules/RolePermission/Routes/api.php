<?php

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
        Route::apiResource('roles', RoleController::class);

        //api resource for permissions CRUD
        Route::apiResource('permissions', PermissionController::class);

        Route::controller(PermissionController::class)->prefix('/permissions')->group(function () {
            Route::get('/user/permissions/{user}', 'userPermissions');
            Route::post('/check/permission', 'checkPermission');
            Route::post('/add/permission/to/role', 'addPermissionToRole');
        });
        Route::controller(RoleController::class)->prefix('/roles')->group(function () {
            Route::post('/update/user/role', 'updateUserRoles');
        });
    });
