<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BtnCallbackController;
use App\Http\Controllers\Api\VaInquiryController;

// API Routes
Route::post('/btn/callback', [BtnCallbackController::class, 'handle']);

// API Virtual Account Inquiry for SNAP Synchronization
Route::get('/va/{va_number}', [VaInquiryController::class, 'show']);
Route::match(['get', 'post'], '/va/inquiry', [VaInquiryController::class, 'inquiry']);