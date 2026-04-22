<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BtnCallbackController;

// API Routes
Route::post('/btn/callback', [BtnCallbackController::class, 'handle']);