<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\AdministrativeFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BtnCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('BTN VA Callback Received:', $payload);

        if (empty($payload)) {
            return response()->json([
                'rsp' => '001',
                'rspdesc' => 'Transaction Failed'
            ]);
        }

        $vaNumber = $payload['va'] ?? null;
        $ref = $payload['ref'] ?? null;

        if (!$vaNumber) {
            return response()->json([
                'rsp' => '001',
                'rspdesc' => 'VA Number Missing'
            ]);
        }

        // Find payment by VA number and Ref
        $payment = Payment::where('va_number', $vaNumber)
            ->where('va_ref', $ref)
            ->where('status', Payment::STATUS_PENDING)
            ->first();

        if (!$payment) {
            // Check if already paid
            $alreadyPaid = Payment::where('va_number', $vaNumber)
                ->where('status', Payment::STATUS_SUCCESS)
                ->exists();

            if ($alreadyPaid) {
                return response()->json([
                    'rsp' => '000',
                    'rspdesc' => 'Transaction Already Processed'
                ]);
            }

            return response()->json([
                'rsp' => '001',
                'rspdesc' => 'Payment Record Not Found'
            ]);
        }

        // Ambil nominal terbayar dari payload jika ada, fallback ke amount tagihan
        $terbayar = $payload['terbayar'] ?? $payment->amount;

        // Update Payment
        $payment->update([
            'status'      => Payment::STATUS_SUCCESS,
            'paid_amount' => $terbayar,
            'verified_at' => now(),
            'admin_note'  => 'Paid via BTN VA Callback'
        ]);

        // Check if it's the first fee (Formulir) to update registration status
        $fee = AdministrativeFee::where('name', $payment->fee_type)
            ->where('educational_level_id', $payment->registration->user->educational_level_id)
            ->first();

        if ($fee && $fee->sort_order == 1) {
            $payment->registration->update(['payment_status' => 'success']);
        } else {
            // Post to SIDIGS for payments other than Formulir
            \App\Services\SidigsService::postStudent($payment->registration);
        }

        return response()->json([
            'rsp' => '000',
            'rspdesc' => 'Transaction Success'
        ]);
    }
}
