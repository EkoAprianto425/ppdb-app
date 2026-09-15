<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Support\AppCache;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function getProvinsi()
    {
        return response()->json(AppCache::regionProvinsi());
    }

    public function getKabupaten(Request $request)
    {
        $provinsi = $request->query('propinsi');
        return response()->json(AppCache::regionKabupaten($provinsi));
    }

    public function getKecamatan(Request $request)
    {
        $kabupaten = $request->query('kabupaten');
        return response()->json(AppCache::regionKecamatan($kabupaten));
    }

    public function getSekolah(Request $request)
    {
        $kecamatan = $request->query('kecamatan');
        return response()->json(AppCache::regionSekolah($kecamatan));
    }
}
