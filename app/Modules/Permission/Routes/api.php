<?php

use App\Modules\Permission\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('permissions', PermissionController::class);
