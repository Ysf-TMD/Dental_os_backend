<?php

use App\Modules\Agenda\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('appointments', AppointmentController::class);
