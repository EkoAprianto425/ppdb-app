<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $registration = Auth::user()->registration;

        if (!$registration) {
            return redirect()->route('pendaftaran.index')->with('error', 'Silakan lengkapi formulir pendaftaran terlebih dahulu.');
        }

        return view('pendaftaran.announcement', compact('registration'));
    }

    public function downloadSKL()
    {
        $registration = Auth::user()->registration;

        if (!$registration || $registration->status !== 'lulus') {
            return back()->with('error', 'Anda belum memenuhi syarat untuk mengunduh Surat Keterangan Lulus.');
        }

        $user = Auth::user();
        
        // Pass the required data to the view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.skl', compact('registration', 'user'));
        
        return $pdf->stream('SKL_' . $user->name . '.pdf');
    }
}
