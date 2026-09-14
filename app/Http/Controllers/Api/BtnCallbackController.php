<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiBankLog;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\AdministrativeFee;
use Illuminate\Http\Request;

class BtnCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        ApiBankLog::write(ApiBankLog::SOURCE_BTN, ApiBankLog::LEVEL_INFO, 'HIT', [
            'ip'      => $request->ip(),
            'payload' => $payload,
        ]);

        if (empty($payload)) {
            ApiBankLog::write(ApiBankLog::SOURCE_BTN, ApiBankLog::LEVEL_WARNING, 'EMPTY_PAYLOAD', [
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'rsp' => '001',
                'rspdesc' => 'Transaction Failed'
            ]);
        }

        $vaNumber = $payload['va'] ?? null;
        $ref      = $payload['ref'] ?? null;

        if (!$vaNumber) {
            ApiBankLog::write(ApiBankLog::SOURCE_BTN, ApiBankLog::LEVEL_WARNING, 'VA_NUMBER_MISSING', [
                'ip'      => $request->ip(),
                'payload' => $payload,
            ]);

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
                ApiBankLog::write(ApiBankLog::SOURCE_BTN, ApiBankLog::LEVEL_INFO, 'ALREADY_PAID', [
                    'va_number' => $vaNumber,
                    'ref'       => $ref,
                    'ip'        => $request->ip(),
                ]);

                return response()->json([
                    'rsp' => '000',
                    'rspdesc' => 'Transaction Already Processed'
                ]);
            }

            ApiBankLog::write(ApiBankLog::SOURCE_BTN, ApiBankLog::LEVEL_WARNING, 'BILL_NOT_FOUND', [
                'va_number' => $vaNumber,
                'ref'       => $ref,
                'ip'        => $request->ip(),
            ]);

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

        ApiBankLog::write(ApiBankLog::SOURCE_BTN, ApiBankLog::LEVEL_INFO, 'SUCCESS', [
            'va_number' => $vaNumber,
            'ref'       => $ref,
            'amount'    => (float) $terbayar,
            'message'   => "BTN callback sukses: fee_type={$payment->fee_type}",
        ]);

        return response()->json([
            'rsp' => '000',
            'rspdesc' => 'Transaction Success'
        ]);
    }
}
