<?php

use App\Modules\Employes\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('employees', EmployeeController::class);
