<?php

use App\Modules\Finance\Controllers\PaymentPlanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('payment-plans', PaymentPlanController::class);
