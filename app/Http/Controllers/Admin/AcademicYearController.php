<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $years = \App\Models\AcademicYear::latest()->get();
        return view('admin.super.academic_years.index', compact('years'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:academic_years']);
        
        \App\Models\AcademicYear::create([
            'name' => $request->name,
            'is_active' => \App\Models\AcademicYear::count() === 0 // Active if first
        ]);
        \Illuminate\Support\Facades\Cache::forget('active_academic_year_id');

        return back()->with('status', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function update(Request $request, \App\Models\AcademicYear $year)
    {
        if ($request->has('activate')) {
            if ($request->activate == '1') {
                \App\Models\AcademicYear::where('id', '!=', $year->id)->update(['is_active' => false]);
                $year->update(['is_active' => true]);
                \Illuminate\Support\Facades\Cache::forget('active_academic_year_id');
                $msg = 'Tahun ajaran berhasil diaktifkan.';
            } else {
                $year->update(['is_active' => false]);
                \Illuminate\Support\Facades\Cache::forget('active_academic_year_id');
                $msg = 'Tahun ajaran berhasil dinonaktifkan.';
            }
            return back()->with('status', $msg);
        }
        
        return back();
    }

    public function destroy(\App\Models\AcademicYear $year)
    {
        if ($year->is_active) {
            return back()->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif.');
        }
        $year->delete();
        return back()->with('status', 'Tahun ajaran berhasil dihapus.');
    }
}
