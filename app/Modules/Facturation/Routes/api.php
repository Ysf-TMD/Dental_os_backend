<?php

use App\Modules\Facturation\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('invoices', InvoiceController::class);
