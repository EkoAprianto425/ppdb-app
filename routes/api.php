<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BtnController;

Route::post('/create-va', [BtnController::class, 'createVA']);