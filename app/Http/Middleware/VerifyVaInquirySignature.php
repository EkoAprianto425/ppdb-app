<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: HMAC-SHA256 Signature Authentication for VA Inquiry API
 *
 * Standar: Bank Indonesia SNAP (Standar Nasional Open API Pembayaran)
 *
 * Request harus menyertakan 3 header:
 *   - X-CLIENT-KEY  : Client identifier yang disetujui
 *   - X-TIMESTAMP   : ISO 8601 timestamp (misal: 2026-08-14T20:28:09+07:00)
 *   - X-SIGNATURE   : HMAC-SHA256("{CLIENT_KEY}|{TIMESTAMP}", CLIENT_SECRET)
 *
 * Fitur keamanan:
 *   1. Client key validation
 *   2. Timestamp window ±N menit (anti replay attack)
 *   3. HMAC-SHA256 signature verification
 *   4. Constant-time comparison (anti timing attack)
 *   5. Audit logging (setiap request dicatat)
 */
class VerifyVaInquirySignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $clientKey  = $request->header('X-CLIENT-KEY');
        $timestamp  = $request->header('X-TIMESTAMP');
        $signature  = $request->header('X-SIGNATURE');

        // --- 1. Pastikan semua header wajib ada ---
        if (empty($clientKey) || empty($timestamp) || empty($signature)) {
            $this->logFailure($request, 'Missing required headers', $clientKey);

            return $this->unauthorizedResponse(
                '4010000',
                'Unauthorized: Header X-CLIENT-KEY, X-TIMESTAMP, dan X-SIGNATURE wajib disertakan.'
            );
        }

        // --- 2. Validasi Client Key ---
        $validClientKey = config('services.va_inquiry.client_key');

        if (empty($validClientKey) || !hash_equals($validClientKey, $clientKey)) {
            $this->logFailure($request, 'Invalid client key', $clientKey);

            return $this->unauthorizedResponse(
                '4010001',
                'Unauthorized: X-CLIENT-KEY tidak valid.'
            );
        }

        // --- 3. Validasi Timestamp (anti replay attack) ---
        try {
            $requestTime = new \DateTime($timestamp);
        } catch (\Exception $e) {
            $this->logFailure($request, 'Invalid timestamp format', $clientKey);

            return $this->unauthorizedResponse(
                '4000001',
                'Bad Request: Format X-TIMESTAMP tidak valid. Gunakan format ISO 8601 (contoh: 2026-08-14T20:28:09+07:00).'
            );
        }

        $now              = new \DateTime('now');
        $toleranceMinutes = (int) config('services.va_inquiry.timestamp_tolerance', 5);
        $diffSeconds      = abs($now->getTimestamp() - $requestTime->getTimestamp());

        if ($diffSeconds > ($toleranceMinutes * 60)) {
            $this->logFailure($request, "Timestamp expired (diff: {$diffSeconds}s)", $clientKey);

            return $this->unauthorizedResponse(
                '4010002',
                "Unauthorized: X-TIMESTAMP sudah kadaluarsa (selisih lebih dari {$toleranceMinutes} menit). Pastikan waktu server sinkron."
            );
        }

        // --- 4. Validasi HMAC-SHA256 Signature ---
        $clientSecret      = config('services.va_inquiry.client_secret');
        $stringToSign      = $clientKey . '|' . $timestamp;
        $expectedSignature = hash_hmac('sha256', $stringToSign, $clientSecret);

        // Constant-time comparison untuk mencegah timing attack
        if (!hash_equals($expectedSignature, strtolower($signature))) {
            $this->logFailure($request, 'Invalid signature', $clientKey);

            return $this->unauthorizedResponse(
                '4010003',
                'Unauthorized: X-SIGNATURE tidak valid. Pastikan string-to-sign dan secret key benar.'
            );
        }

        // --- 5. Request valid -- lanjutkan & catat sukses ---
        $this->logSuccess($request, $clientKey);

        return $next($request);
    }

    /**
     * Buat response JSON unauthorized yang sesuai standar SNAP.
     */
    private function unauthorizedResponse(string $code, string $message): Response
    {
        $httpStatus = str_starts_with($code, '400') ? 400 : 401;

        return response()->json([
            'status'          => false,
            'responseCode'    => $code,
            'responseMessage' => $message,
            'data'            => null,
        ], $httpStatus);
    }

    /**
     * Catat request yang gagal ke log untuk audit trail.
     */
    private function logFailure(Request $request, string $reason, ?string $clientKey): void
    {
        Log::warning('VA Inquiry Auth FAILED', [
            'reason'      => $reason,
            'client_key'  => $clientKey ?? 'N/A',
            'ip'          => $request->ip(),
            'method'      => $request->method(),
            'url'         => $request->fullUrl(),
            'user_agent'  => $request->userAgent(),
            'timestamp'   => now()->toIso8601String(),
        ]);
    }

    /**
     * Catat request yang berhasil ke log untuk audit trail.
     */
    private function logSuccess(Request $request, string $clientKey): void
    {
        Log::info('VA Inquiry Auth OK', [
            'client_key' => $clientKey,
            'ip'         => $request->ip(),
            'method'     => $request->method(),
            'url'         => $request->fullUrl(),
            'timestamp'  => now()->toIso8601String(),
        ]);
    }
}
