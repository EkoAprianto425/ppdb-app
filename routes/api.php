<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BtnCallbackController;
use App\Http\Controllers\Api\VaInquiryController;
use App\Http\Controllers\Api\VaPaymentNotifyController;

// API Routes
Route::post('/btn/callback', [BtnCallbackController::class, 'handle']);

// API Virtual Account — dilindungi HMAC-SHA256 Signature Authentication (standar BI SNAP)
// Setiap request WAJIB menyertakan header:
//   X-CLIENT-KEY : client identifier
//   X-TIMESTAMP  : ISO 8601 timestamp (toleransi ±5 menit)
//   X-SIGNATURE  : HMAC-SHA256("{CLIENT_KEY}|{TIMESTAMP}", CLIENT_SECRET)
Route::middleware(['va.signature', 'throttle:60,1'])->group(function () {
    // --- Inquiry ---
    // PENTING: /va/inquiry harus didefinisikan SEBELUM /va/{va_number}
    // agar Laravel tidak salah menafsirkan string "inquiry" sebagai va_number
    Route::match(['get', 'post'], '/va/inquiry', [VaInquiryController::class, 'inquiry']);
    Route::get('/va/{va_number}', [VaInquiryController::class, 'show']);

    // --- Payment Notification ---
    // Menerima notifikasi pembayaran dari bank/gateway
    // Body JSON: va_number, amount, paid_at, va_ref (opsional), notes (opsional)
    Route::post('/va/payment-notify', [VaPaymentNotifyController::class, 'handle']);
});