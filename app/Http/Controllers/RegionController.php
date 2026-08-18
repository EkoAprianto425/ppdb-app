<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = Sekolah::select('propinsi')
            ->whereNotNull('propinsi')
            ->distinct()
            ->orderBy('propinsi')
            ->pluck('propinsi');
            
        return response()->json($provinsi);
    }

    public function getKabupaten(Request $request)
    {
        $provinsi = $request->query('propinsi');
        
        $kabupaten = Sekolah::where('propinsi', $provinsi)
            ->whereNotNull('kabupaten_kota')
            ->select('kabupaten_kota')
            ->distinct()
            ->orderBy('kabupaten_kota')
            ->pluck('kabupaten_kota');
            
        return response()->json($kabupaten);
    }

    public function getKecamatan(Request $request)
    {
        $kabupaten = $request->query('kabupaten');
        
        $kecamatan = Sekolah::where('kabupaten_kota', $kabupaten)
            ->whereNotNull('kecamatan')
            ->select('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');
            
        return response()->json($kecamatan);
    }

    public function getSekolah(Request $request)
    {
        $kecamatan = $request->query('kecamatan');
        
        $sekolah = Sekolah::where('kecamatan', $kecamatan)
            ->whereNotNull('sekolah')
            ->whereIn('bentuk', ['SD', 'SMP', 'SDLB', 'SLB', 'SMPLB'])
            ->select('sekolah')
            ->distinct()
            ->orderBy('sekolah')
            ->pluck('sekolah');
            
        return response()->json($sekolah);
    }
}
