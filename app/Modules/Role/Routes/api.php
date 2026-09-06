<?php

use App\Modules\Role\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('roles/public', [RoleController::class, 'publicRoles']);

Route::middleware('auth:sanctum')->apiResource('roles', RoleController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('roles/{role}/users/{user}', [RoleController::class, 'assignToUser']);
    Route::delete('roles/{role}/users/{user}', [RoleController::class, 'removeFromUser']);
    Route::get('roles/{role}/users', [RoleController::class, 'users']);
    Route::post('roles/{role}/permissions', [RoleController::class, 'syncPermissions']);
    Route::get('roles/{role}/permissions', [RoleController::class, 'getPermissions']);
    Route::post('roles/{role}/pages', [RoleController::class, 'syncPages']);
    Route::get('roles/{role}/pages', [RoleController::class, 'getPages']);
    Route::post('roles/export', [RoleController::class, 'export']);
    Route::post('roles/import', [RoleController::class, 'import']);
});