<?php

use App\Modules\Traitements\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('treatments', TreatmentController::class);
