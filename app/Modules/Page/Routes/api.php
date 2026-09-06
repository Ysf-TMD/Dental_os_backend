<?php

use App\Modules\Page\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('pages', PageController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('pages/{page}/sync-permissions', [PageController::class, 'syncPermissions']);
    Route::post('pages/auto-detect', [PageController::class, 'autoDetect']);
});
