<?php

use App\Modules\Paiements\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('payments', PaymentController::class);
