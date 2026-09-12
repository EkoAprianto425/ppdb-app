<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = Sekolah::select('propinsi', 'kode_prop')
            ->whereNotNull('propinsi')
            ->distinct()
            ->orderBy('propinsi')
            ->get();
            
        return response()->json($provinsi);
    }

    public function getKabupaten(Request $request)
    {
        $provinsi = $request->query('propinsi');
        
        $kabupaten = Sekolah::where('kode_prop', $provinsi)
            ->whereNotNull('kabupaten_kota')
            ->select('kabupaten_kota', 'kode_kab_kota')
            ->distinct()
            ->orderBy('kabupaten_kota')
            ->get();
            
        return response()->json($kabupaten);
    }

    public function getKecamatan(Request $request)
    {
        $kabupaten = $request->query('kabupaten');
        
        $kecamatan = Sekolah::where('kode_kab_kota', $kabupaten)
            ->whereNotNull('kecamatan')
            ->select('kecamatan', 'kode_kec')
            ->distinct()
            ->orderBy('kecamatan')
            ->get();
            
        return response()->json($kecamatan);
    }

    public function getSekolah(Request $request)
    {
        $kecamatan = $request->query('kecamatan');
        
        $sekolah = Sekolah::where('kode_kec', $kecamatan)
            ->whereNotNull('sekolah')
            ->whereIn('bentuk', ['SD', 'SMP', 'SDLB', 'SLB', 'SMPLB'])
            ->select('sekolah', 'propinsi', 'kabupaten_kota', 'kecamatan')
            ->distinct()
            ->orderBy('sekolah')
            ->get();
            
        return response()->json($sekolah);
    }
}
