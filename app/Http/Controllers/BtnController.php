<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BtnService;

class BtnController extends Controller
{
    protected BtnService $btnService;

    public function __construct(BtnService $btnService)
    {
        $this->btnService = $btnService;
    }

    public function createVA(Request $request)
    {
        $validated = $request->validate([
            'ref' => 'required|string',
            'va' => 'required|string',
            'nama_siswa' => 'required|string',
            'jenis_bayar' => 'required|string',
            'no_id' => 'required|string',
            'tagihan' => 'required|numeric',
            'flag' => 'required|string',
            'id_calsis' => 'required|integer',
            'no_urut' => 'required|string',
        ]);
        
        $result = $this->btnService->createVA($validated);

        if ($result['status']) {
            return response()->json([
                'success' => true,
                'message' => 'VA berhasil dibuat',
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'messages' => $result['messages'],
            'code' => $result['code'] ?? null
        ], 400);
    }
}