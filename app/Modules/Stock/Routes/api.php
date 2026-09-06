<?php

use App\Modules\Stock\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('products', ProductController::class);
