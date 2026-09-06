<?php

use App\Modules\Devis\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('quotes', QuoteController::class);
