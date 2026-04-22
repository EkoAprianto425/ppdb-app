<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class BtnService
{
    protected string $id;
    protected string $key;
    protected string $secret;
    protected string $baseUrl;

    public function __construct()
    {
        $this->id = config('btn.id');
        $this->key = config('btn.key');
        $this->secret = config('btn.secret');
        $this->baseUrl = config('btn.base_url');
    }

    public function createVA(array $data): array
    {
        $payload = $this->buildPayload($data);
        $signature = $this->generateSignature($payload);

        $response = Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'id' => $this->id,
                'key' => $this->key,
                'signature' => $signature,
            ])
            ->post($this->baseUrl.'/createVA', $payload);

        return $this->handleResponse($response);
    }

    public function inquiryVA(array $data): array
    {
        $payload = $this->buildPayload($data);
        $signature = $this->generateSignature($payload);

        $response = Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'id' => $this->id,
                'key' => $this->key,
                'signature' => $signature,
            ])
            ->post($this->baseUrl.'/inqVA', $payload);

        return $this->handleResponse($response);
    }

    public function updateVA(array $data): array
    {
        $payload = $this->buildPayload($data);
        $signature = $this->generateSignature($payload);

        $response = Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'id' => $this->id,
                'key' => $this->key,
                'signature' => $signature,
            ])
            ->post($this->baseUrl.'/updVA', $payload);

        return $this->handleResponse($response);
    }

    public function deleteVA(array $data): array
    {
        $payload = $this->buildPayload($data);
        $signature = $this->generateSignature($payload);

        $response = Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'id' => $this->id,
                'key' => $this->key,
                'signature' => $signature,
            ])
            ->post($this->baseUrl.'/deleteVA', $payload);

        return $this->handleResponse($response);
    }

    public function report(): array
    {
        $response = Http::withHeaders([
                'id' => $this->id,
                'key' => $this->key,
            ])
            ->get($this->baseUrl.'/report');

        return $this->handleResponse($response);
    }

    private function buildPayload(array $data): array
    {
        return [
            'ref' => $data['ref'],
            'va' => $data['va'],
            'nama' => $data['nama_siswa'],
            'layanan' => 'PPDB',
            'kodelayanan' => '',
            'jenisbayar' => $data['jenis_bayar'],
            'kodejenisbyr' => '',
            'nogiro' => '',
            'noid' => $data['no_id'],
            'tagihan' => $data['tagihan'],
            'flag' => $data['flag'],
            'expired' => '',
            'reserve' => '',
            'angkatan' => '',
            'description' => ''
        ];
    }

    /**
     * Generate signature sesuai format BTN API.
     * Signature = HMAC-SHA256( id:{json_payload}:key, secret )
     * JSON payload dibuat secara manual TANPA spasi antara key dan value,
     * dan SEMUA value di-cast sebagai string (dalam tanda kutip).
     */
    private function generateSignature(array $payload): string
    {
        // Build JSON string secara manual, semua value sebagai string, tanpa spasi
        $json = '{"ref":"'.$payload['ref'].'","va":"'.$payload['va'].'","nama":"'.$payload['nama'].'","layanan":"'.$payload['layanan'].'","kodelayanan":"'.$payload['kodelayanan'].'","jenisbayar":"'.$payload['jenisbayar'].'","kodejenisbyr":"'.$payload['kodejenisbyr'].'","nogiro":"'.$payload['nogiro'].'","noid":"'.$payload['noid'].'","tagihan":"'.$payload['tagihan'].'","flag":"'.$payload['flag'].'","expired":"'.$payload['expired'].'","reserve":"'.$payload['reserve'].'","angkatan":"'.$payload['angkatan'].'","description":"'.$payload['description'].'"}';

        $stringToSign = $this->id.':'.$json.':'.$this->key;

        return hash_hmac('SHA256', $stringToSign, $this->secret);
    }

    private function handleResponse($response): array
    {
        if (!$response->successful()) {
            return [
                'status' => false,
                'message' => 'HTTP Error',
                'data' => $response->body()
            ];
        }

        $rsp = $response->json();

        if (($rsp['rsp'] ?? null) === "000") {
            return [
                'status' => true,
                'data' => $rsp
            ];
        }

        return [
            'status' => false,
            'messages' => $rsp['rspdesc'] ?? 'Unknown error',
            'code' => $rsp['rsp'] ?? null
        ];
    }

    // private function storeTransaction(array $data, array $rsp): void
    // {
    //     DB::table('ppdb_transaksi')->insert([
    //         'transaksi_calsis' => $data['id_calsis'],
    //         'transaksi_adm'    => $data['no_urut'],
    //         'transaksi_harga'  => $data['tagihan'],
    //         'transaksi_va'     => $rsp['va'],
    //         'created_at'       => now(),
    //         'updated_at'       => now(),
    //     ]);
    // }
}