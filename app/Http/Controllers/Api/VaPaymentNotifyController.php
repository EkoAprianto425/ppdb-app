<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\AdministrativeFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Controller: VA Payment Notification
 *
 * Menerima notifikasi pembayaran Virtual Account dari pihak bank/gateway.
 * POST /api/va/payment-notify
 *
 * Endpoint ini dilindungi middleware va.signature (HMAC-SHA256).
 */
class VaPaymentNotifyController extends Controller
{
    /**
     * Terima notifikasi pembayaran VA dan update status payment.
     *
     * Body JSON wajib:
     *   - va_number   : string  — Nomor Virtual Account
     *   - amount      : numeric — Nominal yang dibayarkan
     *   - paid_at     : string  — Waktu bayar (ISO 8601 / Y-m-d H:i:s)
     *
     * Body JSON opsional:
     *   - va_ref      : string  — Nomor referensi transaksi dari bank
     *   - notes       : string  — Catatan tambahan dari bank
     */
    public function handle(Request $request)
    {
        // --- 1. Validasi input ---
        $validator = Validator::make($request->all(), [
            'va_number' => ['required', 'string', 'max:30'],
            'amount'    => ['required', 'numeric', 'min:1'],
            'paid_at'   => ['required', 'date'],
            'va_ref'    => ['nullable', 'string', 'max:100'],
            'notes'     => ['nullable', 'string', 'max:500'],
        ], [
            'va_number.required' => 'Nomor Virtual Account (va_number) wajib diisi.',
            'amount.required'    => 'Nominal pembayaran (amount) wajib diisi.',
            'amount.numeric'     => 'Nominal pembayaran (amount) harus berupa angka.',
            'amount.min'         => 'Nominal pembayaran (amount) harus lebih dari 0.',
            'paid_at.required'   => 'Waktu pembayaran (paid_at) wajib diisi.',
            'paid_at.date'       => 'Format waktu pembayaran (paid_at) tidak valid.',
        ]);

        if ($validator->fails()) {
            Log::warning('VA Payment Notify: Validation failed', [
                'errors'    => $validator->errors()->toArray(),
                'ip'        => $request->ip(),
                'payload'   => $request->except([]),
            ]);

            return response()->json([
                'status'          => false,
                'responseCode'    => '4002500',
                'responseMessage' => 'Bad Request: Data tidak valid.',
                'errors'          => $validator->errors(),
                'data'            => null,
            ], 422);
        }

        $vaNumber = $request->input('va_number');
        $amount   = (float) $request->input('amount');
        $paidAt   = $request->input('paid_at');
        $vaRef    = $request->input('va_ref');
        $notes    = $request->input('notes');

        // --- 2. Cari record payment berdasarkan VA number ---
        $payment = Payment::with(['registration.user'])
            ->where('va_number', $vaNumber)
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->first();

        if (!$payment) {
            // Cek apakah sudah pernah dibayar sebelumnya
            $alreadyPaid = Payment::where('va_number', $vaNumber)
                ->where('status', Payment::STATUS_SUCCESS)
                ->exists();

            if ($alreadyPaid) {
                Log::info('VA Payment Notify: Already paid', ['va_number' => $vaNumber]);

                return response()->json([
                    'status'          => true,
                    'responseCode'    => '2002501',
                    'responseMessage' => 'Payment Already Processed',
                    'message'         => 'Pembayaran untuk VA ini sudah diproses sebelumnya.',
                    'data'            => null,
                ], 200);
            }

            Log::warning('VA Payment Notify: VA not found', ['va_number' => $vaNumber]);

            return response()->json([
                'status'          => false,
                'responseCode'    => '4042512',
                'responseMessage' => 'Bill not found',
                'message'         => 'Data Virtual Account tidak ditemukan atau bukan dalam status pending.',
                'data'            => null,
            ], 404);
        }

        // --- 3. Validasi kesesuaian nominal (toleransi ±1 rupiah untuk pembulatan) ---
        $expectedAmount = (float) $payment->amount;
        if (abs($amount - $expectedAmount) > 1) {
            Log::warning('VA Payment Notify: Amount mismatch', [
                'va_number' => $vaNumber,
                'expected'  => $expectedAmount,
                'received'  => $amount,
            ]);

            return response()->json([
                'status'          => false,
                'responseCode'    => '4002501',
                'responseMessage' => 'Bad Request: Nominal tidak sesuai.',
                'message'         => "Nominal yang dibayarkan (Rp " . number_format($amount, 0, ',', '.') . ") tidak sesuai dengan tagihan (Rp " . number_format($expectedAmount, 0, ',', '.') . ").",
                'data'            => null,
            ], 422);
        }

        // --- 4. Update status payment ke sukses ---
        $payment->update([
            'status'      => Payment::STATUS_SUCCESS,
            'va_ref'      => $vaRef ?? $payment->va_ref,
            'verified_at' => $paidAt,
            'admin_note'  => $notes ?? ('Dibayar via API notifikasi pada ' . $paidAt),
        ]);

        // --- 5. Update payment_status registration jika ini fee pertama (Formulir) ---
        $fee = AdministrativeFee::where('name', $payment->fee_type)
            ->where('educational_level_id', $payment->registration?->user?->educational_level_id)
            ->first();

        if ($fee && $fee->sort_order == 1) {
            $payment->registration->update(['payment_status' => 'success']);
        }

        $user = $payment->registration?->user;
        $namaSiswa = $user?->full_name
            ?: ($user?->name
                ?: ($payment->registration?->nama_panggilan ?? 'N/A'));

        Log::info('VA Payment Notify: SUCCESS', [
            'va_number'  => $vaNumber,
            'amount'     => $amount,
            'paid_at'    => $paidAt,
            'payment_id' => $payment->id,
            'siswa'      => $namaSiswa,
        ]);

        return response()->json([
            'status'          => true,
            'responseCode'    => '2002500',
            'responseMessage' => 'Successful',
            'message'         => 'Pembayaran berhasil diproses.',
            'data'            => [
                'va_number'   => $payment->va_number,
                'nama_siswa'  => $namaSiswa,
                'fee_type'    => $payment->fee_type,
                'amount'      => (float) $payment->amount,
                'paid_amount' => $amount,
                'paid_at'     => $paidAt,
                'status'      => $payment->status,
            ],
        ], 200);
    }
}
