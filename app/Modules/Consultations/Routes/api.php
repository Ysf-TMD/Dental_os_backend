<?php

use App\Modules\Consultations\Controllers\ConsultationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('consultations', ConsultationController::class);
