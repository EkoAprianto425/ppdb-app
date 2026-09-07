<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SidigsRecord;
use App\Models\Registration;
use Illuminate\Support\Facades\Log;

class SidigsService
{
    public static function postStudent(Registration $registration)
    {
        $level = $registration->user->educationalLevel->name ?? '';
        
        // Map keys based on level
        $clientKey = '';
        $secretKey = '';
        
        if (str_contains(strtoupper($level), 'SMP')) {
            $clientKey = 'SGS256';
            $secretKey = '2ReFRvOtBmWbnFXFAkl2kjRqgxPMfPESXetq4cgV';
        } elseif (str_contains(strtoupper($level), 'SMA')) {
            $clientKey = 'SGS257';
            $secretKey = 'EfLaRZNumnkdi3bebfU6OGpKN50oJZpkZ4lKISoe';
        } elseif (str_contains(strtoupper($level), 'SMK')) {
            $clientKey = 'SGS258';
            $secretKey = '4vFqW0qH3w2OvM6FMRGERXw2QEWiYnMjYPApENmn';
        } else {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'X-Client-Key' => $clientKey,
                'X-Secret-Key' => $secretKey,
                'Accept' => 'application/json'
            ])->post('https://sidigs.com/api/students', [
                'name' => $registration->nama_lengkap,
                'nisn' => $registration->nisn,
                'email' => $registration->user->email ?? '',
                'phone' => $registration->no_hp ?? '',
                'gender' => $registration->jenis_kelamin ?? '',
                'address' => $registration->alamat_lengkap ?? '',
                'birth_place' => $registration->tempat_lahir ?? '',
                'birth_date' => $registration->tanggal_lahir ?? '',
            ]);

            SidigsRecord::create([
                'registration_id' => $registration->id,
                'status' => $response->successful() ? 'success' : 'failed',
                'response_payload' => $response->json() ?? ['body' => $response->body()],
            ]);

            return $response->successful();
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
