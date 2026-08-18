<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolReason;
use Illuminate\Http\Request;

class SchoolReasonController extends Controller
{
    public function index()
    {
        $reasons = SchoolReason::latest()->get();
        return view('admin.school_reasons.index', compact('reasons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:school_reasons,name',
        ]);

        SchoolReason::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Alasan memilih sekolah berhasil ditambahkan.');
    }

    public function update(Request $request, SchoolReason $school_reason)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:school_reasons,name,' . $school_reason->id,
        ]);

        $school_reason->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Alasan memilih sekolah berhasil diperbarui.');
    }

    public function destroy(SchoolReason $school_reason)
    {
        $school_reason->delete();
        return redirect()->back()->with('success', 'Alasan memilih sekolah berhasil dihapus.');
    }
}
