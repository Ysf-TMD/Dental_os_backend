<?php

use App\Modules\Patients\Controllers\PatientController;
use App\Modules\Patients\Controllers\PatientDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('patients', PatientController::class);
Route::middleware('auth:sanctum')->post('patients/{patient}/documents', [PatientDocumentController::class, 'store']);
Route::middleware('auth:sanctum')->delete('documents/{document}', [PatientDocumentController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('patients/{patient}/financial-summary', [PatientController::class, 'financialSummary']);
Route::middleware('auth:sanctum')->get('patients/{patient}/financial-transactions', [PatientController::class, 'financialTransactions']);
Route::middleware('auth:sanctum')->get('patients/{patient}/payment-plans', [PatientController::class, 'paymentPlans']);
