<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalLevel;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levels = EducationalLevel::orderBy('sort_order')->get();
        return view('admin.levels.index', compact('levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_unit' => 'required|in:SMP,SMA,SMK',
            'contact_whatsapp' => 'nullable|string|max:20',
            'sort_order' => 'required|integer',
        ]);

        if (!empty($validated['contact_whatsapp'])) {
            $validated['contact_whatsapp'] = preg_replace('/[^0-9]/', '', $validated['contact_whatsapp']);
        }

        EducationalLevel::create($validated);

        return back()->with('status', 'Jenjang berhasil ditambahkan.');
    }

    public function update(Request $request, EducationalLevel $level)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_unit' => 'required|in:SMP,SMA,SMK',
            'contact_whatsapp' => 'nullable|string|max:20',
            'sort_order' => 'required|integer',
        ]);

        if (!empty($validated['contact_whatsapp'])) {
            $validated['contact_whatsapp'] = preg_replace('/[^0-9]/', '', $validated['contact_whatsapp']);
        }

        $level->update($validated);

        return back()->with('status', 'Jenjang berhasil diperbarui.');
    }

    public function destroy(EducationalLevel $level)
    {
        if ($level->fees()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus jenjang yang memiliki data biaya administrasi.');
        }
        
        $level->delete();
        return back()->with('status', 'Jenjang berhasil dihapus.');
    }
}
