<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InformationSource;
use Illuminate\Http\Request;

class InformationSourceController extends Controller
{
    public function index()
    {
        $sources = InformationSource::latest()->get();
        return view('admin.information_sources.index', compact('sources'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:information_sources,name',
        ]);

        InformationSource::create([
            'name' => $request->name,
            'is_active' => true,
            'requires_manual_input' => $request->has('requires_manual_input'),
        ]);

        return redirect()->back()->with('success', 'Sumber informasi berhasil ditambahkan.');
    }

    public function update(Request $request, InformationSource $information_source)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:information_sources,name,' . $information_source->id,
        ]);

        $information_source->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
            'requires_manual_input' => $request->has('requires_manual_input'),
        ]);

        return redirect()->back()->with('success', 'Sumber informasi berhasil diperbarui.');
    }

    public function destroy(InformationSource $information_source)
    {
        // Check if source is used in any user record to prevent accidental deletion if needed
        // For now, simple delete
        $information_source->delete();

        return redirect()->back()->with('success', 'Sumber informasi berhasil dihapus.');
    }
}
