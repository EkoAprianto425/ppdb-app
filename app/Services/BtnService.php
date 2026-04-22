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
        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $signature = $this->generateSignature($jsonPayload);

        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'id' => $this->id,
                'key' => $this->key,
                'signature' => $signature,
            ])
            ->withBody($jsonPayload, 'application/json')
            ->post($this->baseUrl . '/createVA');

        if (!$response->successful()) {
            return [
                'status' => false,
                'message' => 'HTTP Error',
                'data' => $response->body()
            ];
        }

        $rsp = $response->json();

        if (($rsp['rsp'] ?? null) === "000") {
            // $this->storeTransaction($data, $rsp);

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

    private function buildPayload(array $data): array
    {
        return [
            'ref'           => $data['ref'],
            'va'            => $data['va'],
            'nama'          => $data['nama_siswa'],
            'layanan'       => 'PPDB',
            'kodelayanan'   => '',
            'jenisbayar'    => $data['jenis_bayar'],
            'kodejenisbyr'  => '',
            'nogiro'        => '',
            'noid'          => $data['no_id'],
            'tagihan'       => $data['tagihan'],
            'flag'          => $data['flag'],
            'expired'       => '',
            'reserve'       => '',
            'angkatan'      => '',
            'description'   => ''
        ];
    }

    private function generateSignature(string $jsonPayload): string
    {
        $stringToSign = $this->id . ':' . $jsonPayload . ':' . $this->key;

        return hash_hmac('SHA256', $stringToSign, $this->secret);
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