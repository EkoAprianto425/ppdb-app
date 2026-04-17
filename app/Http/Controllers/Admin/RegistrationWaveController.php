<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrationWaveController extends Controller
{
    public function index()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->route('admin.year.index')->with('error', 'Silakan aktifkan tahun ajaran terlebih dahulu.');
        }

        $waves = $activeYear->waves()->latest()->get();
        return view('admin.super.waves.index', compact('waves', 'activeYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        \App\Models\RegistrationWave::create([
            'academic_year_id' => $request->academic_year_id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => \App\Models\RegistrationWave::where('academic_year_id', $request->academic_year_id)->count() === 0
        ]);

        return back()->with('status', 'Gelombang pendaftaran berhasil ditambahkan.');
    }

    public function update(Request $request, \App\Models\RegistrationWave $wave)
    {
        if ($request->has('activate')) {
            \App\Models\RegistrationWave::where('academic_year_id', $wave->academic_year_id)
                ->where('id', '!=', $wave->id)
                ->update(['is_active' => false]);
            $wave->update(['is_active' => true]);
        }
        
        return back()->with('status', 'Gelombang pendaftaran berhasil diaktifkan.');
    }

    public function destroy(\App\Models\RegistrationWave $wave)
    {
        if ($wave->is_active) {
            return back()->with('error', 'Tidak dapat menghapus gelombang yang sedang aktif.');
        }
        $wave->delete();
        return back()->with('status', 'Gelombang pendaftaran berhasil dihapus.');
    }
}
