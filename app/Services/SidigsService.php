<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SidigsRecord;
use App\Models\Registration;
use Illuminate\Support\Facades\Log;

class SidigsService
{
    // ponytail: hardcoded keys — move to config/env when multi-tenant needed
    private const SCHOOL_MAP = [
        'SMP' => ['client_key' => 'SGS256', 'secret_key' => '2ReFRvOtBmWbnFXFAkl2kjRqgxPMfPESXetq4cgV'],
        'SMA' => ['client_key' => 'SGS257', 'secret_key' => 'EfLaRZNumnkdi3bebfU6OGpKN50oJZpkZ4lKISoe'],
        'SMK' => ['client_key' => 'SGS258', 'secret_key' => '4vFqW0qH3w2OvM6FMRGERXw2QEWiYnMjYPApENmn'],
    ];

    private const API_BASE = 'https://sidigs.com/api/v1';

    public static function postStudent(Registration $registration)
    {
        $level = $registration->user->educationalLevel->parent_unit ?? '';
        $school = self::SCHOOL_MAP[strtoupper($level)] ?? null;

        if (!$school) {
            return false;
        }

        // Derive grade from parent_unit: SMP = 7, SMA/SMK = 10
        $grade = strtoupper($level) === 'SMP' ? '7' : '10';

        // For SMK, extract class_name from educational level name (e.g. "SMK Akuntansi" → "Akuntansi")
        $className = null;
        $levelName = $registration->user->educationalLevel->name ?? '';
        if (strtoupper($level) === 'SMK' && $levelName !== 'SMK') {
            $className = trim(str_ireplace('SMK', '', $levelName));
        }

        // Map gender: database stores full string, API expects L/P
        $genderRaw = $registration->jenis_kelamin ?? '';
        $gender = match (true) {
            str_contains(strtoupper($genderRaw), 'LAKI') => 'L',
            str_contains(strtoupper($genderRaw), 'PEREM') => 'P',
            in_array(strtoupper($genderRaw), ['L', 'P']) => strtoupper($genderRaw),
            default => $genderRaw,
        };

        $body = array_filter([
            'name'       => $registration->user->full_name,
            'nisn'       => $registration->nisn ?? "-",
            'nickname'   => $registration->nama_panggilan ?? null,
            'gender'     => $gender,
            'birthplace' => $registration->tempat_lahir ?? null,
            'birthdate'  => $registration->tanggal_lahir,
            'religion'   => $registration->agama ?? null,
            'address'    => $registration->alamat ?? null,
            'phone'      => $registration->user->whatsapp_number ?? null,
            'grade'      => $grade,
            'class_name' => $className,
            'wali'       => [
                'name'  => $registration->nama_ayah ?? $registration->nama_ibu ?? 'Wali',
                'phone' => $registration->user->whatsapp_number ?? null,
            ],
        ], fn ($v) => $v !== null && $v !== '');

        // Wali is required even if mostly empty — ensure it stays
        if (!isset($body['wali'])) {
            $body['wali'] = ['name' => $registration->nama_ayah ?? $registration->nama_ibu ?? 'Wali'];
        }

        $path = '/api/v1/students';
        $method = 'POST';
        $timestamp = (string) time();
        $rawBody = json_encode($body);
        $payload = $method . $path . $rawBody . $timestamp;
        $signature = hash_hmac('sha256', $payload, $school['secret_key']);

        try {
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'X-CLIENT-KEY'  => $school['client_key'],
                'X-TIMESTAMP'   => $timestamp,
                'X-SIGNATURE'   => $signature,
                'Accept'        => 'application/json',
            ])->withBody($rawBody, 'application/json')
              ->post(self::API_BASE . '/students');

            $responseData = $response->json();
            $responseCode = $responseData['responseCode'] ?? null;
            $isSuccess = $responseCode === '000200';

            SidigsRecord::create([
                'registration_id' => $registration->id,
                'status' => $isSuccess ? 'success' : 'failed',
                'response_payload' => $responseData ?? ['body' => $response->body()],
            ]);

            if ($isSuccess) {
                Log::info('SIDIGS: Siswa berhasil didaftarkan', [
                    'registration_id' => $registration->id,
                    'student_id'      => $responseData['data']['student_id'] ?? null,
                    'class_name'      => $responseData['data']['class_name'] ?? null,
                    'student_username' => $responseData['data']['student_account']['username'] ?? null,
                    'wali_username'    => $responseData['data']['wali_account']['username'] ?? null,
                ]);
            } else {
                Log::warning('SIDIGS: Gagal mendaftarkan siswa', [
                    'registration_id' => $registration->id,
                    'responseCode'    => $responseCode,
                    'responseMessage' => $responseData['responseMessage'] ?? $response->body(),
                ]);
            }

            return $isSuccess;
        } catch (\Exception $e) {
            Log::error('SIDIGS Post Error: ' . $e->getMessage());
            SidigsRecord::create([
                'registration_id' => $registration->id,
                'status' => 'failed',
                'response_payload' => ['error' => $e->getMessage()],
            ]);
            return false;
        }
    }
}
