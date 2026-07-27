<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class VaInquiryController extends Controller
{
    /**
     * Get Virtual Account data by VA number (Path parameter).
     * GET /api/va/{va_number}
     */
    public function show(string $va_number)
    {
        return $this->processInquiry($va_number);
    }

    /**
     * Get Virtual Account data by Request parameter/body.
     * GET / POST /api/va/inquiry
     */
    public function inquiry(Request $request)
    {
        $vaNumber = $request->input('va')
            ?? $request->input('va_number')
            ?? $request->input('virtualAccountNumber');

        if (empty($vaNumber)) {
            return response()->json([
                'status' => false,
                'responseCode' => '4002500',
                'responseMessage' => 'Bad Request: Nomor Virtual Account (va) tidak diberikan.',
                'message' => 'Parameter va / va_number / virtualAccountNumber wajib diisi.',
                'data' => null
            ], 400);
        }

        return $this->processInquiry($vaNumber);
    }

    /**
     * Core logic to query Payment and format response for SNAP VA synchronization.
     */
    protected function processInquiry(string $vaNumber)
    {
        $payment = Payment::with(['registration.user'])
            ->where('va_number', $vaNumber)
            ->latest()
            ->first();

        if (!$payment) {
            return response()->json([
                'status' => false,
                'responseCode' => '4042512',
                'responseMessage' => 'Bill not found',
                'message' => 'Data Virtual Account tidak ditemukan.',
                'data' => null
            ], 404);
        }

        $user = $payment->registration?->user;
        $namaSiswa = $user?->full_name
            ?: ($user?->name
                ?: ($payment->registration?->nama_panggilan ?: 'N/A'));

        return response()->json([
            'status' => true,
            'responseCode' => '2002500',
            'responseMessage' => 'Successful',
            'message' => 'Data Virtual Account berhasil ditemukan.',
            'data' => [
                // 1. Nama lengkap siswa
                'nama_siswa' => $namaSiswa,

                // 2. Fee Type
                'fee_type'   => $payment->fee_type,

                // 3. VA
                'va'         => $payment->va_number,

                // 4. Nominal
                'nominal'    => (float) $payment->amount,

                // Metadata tambahan untuk sinkronisasi SNAP
                'status'     => $payment->status
            ]
        ], 200);
    }
}
