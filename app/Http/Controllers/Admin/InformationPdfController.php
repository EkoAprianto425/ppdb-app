<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InformationPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationPdfController extends Controller
{
    public function index()
    {
        $pdfs = InformationPdf::all();
        $types = [
            'brosur_smp' => 'Brosur SMP',
            'brosur_sma' => 'Brosur SMA',
            'brosur_smk' => 'Brosur SMK',
            'info_biaya_smp' => 'Info Biaya SMP',
            'info_biaya_sma' => 'Info Biaya SMA',
            'info_biaya_smk' => 'Info Biaya SMK',
            'kisi_kisi_ujian_smp' => 'Kisi-kisi Ujian SMP',
            'kisi_kisi_ujian_sma' => 'Kisi-kisi Ujian SMA',
            'kisi_kisi_ujian_smk' => 'Kisi-kisi Ujian SMK',
        ];

        return view('admin.super.information_pdfs.index', compact('pdfs', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:brosur_smp,brosur_sma,brosur_smk,info_biaya_smp,info_biaya_sma,info_biaya_smk,kisi_kisi_ujian_smp,kisi_kisi_ujian_sma,kisi_kisi_ujian_smk',
            'file' => 'required|file|mimes:pdf|max:51200', // max 50MB
        ]);

        $file = $request->file('file');
        $fileName = $request->type . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(storage_path('app/public/information_pdfs'), $fileName);
        $path = 'information_pdfs/' . $fileName;

        // Check if a PDF of this type already exists, if so delete old one
        $existing = InformationPdf::where('type', $request->type)->first();
        if ($existing) {
            $oldFile = storage_path('app/public/' . $existing->file_path);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
            $existing->delete();
        }

        InformationPdf::create([
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
        ]);

        return back()->with('status', 'File PDF berhasil diunggah.');
    }

    public function destroy(InformationPdf $informationPdf)
    {
        $filePath = storage_path('app/public/' . $informationPdf->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $informationPdf->delete();

        return back()->with('status', 'File PDF berhasil dihapus.');
    }
}
