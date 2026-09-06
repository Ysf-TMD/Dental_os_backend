<?php

use App\Modules\Laboratoire\Controllers\LabOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('lab-orders', LabOrderController::class);
